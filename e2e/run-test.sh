#!/usr/bin/env bash

set -x

TESTS_BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd)"
BASE_DIR="$TESTS_BASE_DIR/../"

cd $TESTS_BASE_DIR

#rm -r composer.lock vendor || true
composer install

run_test() {
  TEST_DIR=$1

  set +x
  echo
  echo "######################################################"
  echo "# Running test in $TEST_DIR"
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
    $TESTS_BASE_DIR/$TEST_DIR/run.sh
  else
    ./vendor/bin/lifter run --file=$TESTS_BASE_DIR/$TEST_DIR/lifter.php > $TESTS_BASE_DIR/$TEST_DIR/output.txt
  fi

  diff -ub $TEST_DIR/expected-output.txt $TEST_DIR/output.txt
  diff -rub $TEST_DIR/expected-output/ $TEST_DIR/output/
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
