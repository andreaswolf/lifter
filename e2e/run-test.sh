#!/usr/bin/env bash

set -x
set -e

export TESTS_BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd)"
BASE_DIR="$TESTS_BASE_DIR/../"

cd $TESTS_BASE_DIR

#rm -r composer.lock vendor || true
composer install

fail() {
  echo "Test failed"
  exit 1
}

run_test() {
  export TEST_DIR=$1

  set +x
  echo
  echo "######################################################"
  echo "# Running test $TEST_DIR"
  echo "######################################################"
  echo
  set -x

  cd $TESTS_BASE_DIR/$TEST_DIR

  [[ -f $TESTS_BASE_DIR/$TEST_DIR/output.txt ]] && rm -f $TESTS_BASE_DIR/$TEST_DIR/output.txt
  [[ -d ./result/ ]] && rm -rf ./result/
  cp -r fixtures/ result/

  cd $TESTS_BASE_DIR
  if [[ -x $TESTS_BASE_DIR/$TEST_DIR/run.sh ]]
  then
    ($TESTS_BASE_DIR/$TEST_DIR/run.sh > $TESTS_BASE_DIR/$TEST_DIR/output.txt) || (echo "Test $TEST_DIR failed"; fail)
  else
    (./vendor/bin/lifter run --file=$TESTS_BASE_DIR/$TEST_DIR/lifter.php > $TESTS_BASE_DIR/$TEST_DIR/output.txt) || (echo "Test $TEST_DIR failed"; fail)
  fi

  diff -ub $TEST_DIR/expected-output.txt $TEST_DIR/output.txt || (echo "Program output does not match expectation"; fail)
  diff -rub $TEST_DIR/expected-result/ $TEST_DIR/result/ || (echo "Produced result does not match expectations"; fail)
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
