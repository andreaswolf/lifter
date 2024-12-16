#!/usr/bin/env bash

set -x
set -e

export TESTS_BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd)"
BASE_DIR="$TESTS_BASE_DIR/../"

cd $TESTS_BASE_DIR

#rm -r composer.lock vendor || true
composer install

run_test() {
  export TEST_DIR=$1

  SUCCESS=1

  set +x
  echo
  echo "######################################################"
  echo "# Running test $TEST_DIR"
  echo "######################################################"
  echo
  set -x

  cd $TESTS_BASE_DIR/$TEST_DIR

  [[ -f $TESTS_BASE_DIR/$TEST_DIR/output.txt ]] && rm -f $TESTS_BASE_DIR/$TEST_DIR/output.txt
  [[ -d ./output/ ]] && rm -rf ./output/
  cp -r fixtures/ output/

  cd $TESTS_BASE_DIR
  if [[ -x $TESTS_BASE_DIR/$TEST_DIR/run.sh ]]
  then
    ($TESTS_BASE_DIR/$TEST_DIR/run.sh > $TESTS_BASE_DIR/$TEST_DIR/output.txt) || (echo "Test $TEST_DIR failed"; SUCCESS=0)
  else
    (./vendor/bin/lifter run --file=$TESTS_BASE_DIR/$TEST_DIR/lifter.php > $TESTS_BASE_DIR/$TEST_DIR/output.txt) || (echo "Test $TEST_DIR failed"; SUCCESS=0)
  fi

  diff -ub $TEST_DIR/expected-output.txt $TEST_DIR/output.txt || (echo "Program output does not match expectation"; SUCCESS=0)
  diff -rub $TEST_DIR/expected-output/ $TEST_DIR/output/ || (echo "Produced result does not match expectations"; SUCCESS=0)

  if [[ $SUCCESS -eq 0 ]]
  then
    echo "Test failed"
    exit 1
  fi
}

if [ $# -eq 0 ]
then
  run_test rector
  run_test fractor
else
  for test in $@
  do
    run_test $test
  done
fi
