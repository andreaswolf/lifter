#!/usr/bin/env bash

set -x
set -e

export TESTS_BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd)"
BASE_DIR="$TESTS_BASE_DIR/../"

cd $TESTS_BASE_DIR

#rm -r composer.lock vendor || true
composer install

SUCCESS=1

fail() {
  echo $1

  SUCCESS=0
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

  OUTPUT_FILE=$TESTS_BASE_DIR/$TEST_DIR/output.txt

  [[ -f $OUTPUT_FILE ]] && rm -f $OUTPUT_FILE
  [[ -d ./result/ ]] && rm -rf ./result/
  [[ -d ./fixtures ]] && cp -r fixtures/ result/

  cd $TESTS_BASE_DIR
  if [[ -x $TESTS_BASE_DIR/$TEST_DIR/run.sh ]]
  then
    ($TESTS_BASE_DIR/$TEST_DIR/run.sh > $OUTPUT_FILE) || fail "Test $TEST_DIR failed"
  else
    (./vendor/bin/lifter run --file=$TESTS_BASE_DIR/$TEST_DIR/lifter.php > $OUTPUT_FILE) || fail "Test $TEST_DIR failed"
  fi

  sed -i -e "s#$TESTS_BASE_DIR/$TEST_DIR#<test-dir>#g" $OUTPUT_FILE

  diff -ub $TEST_DIR/expected-output.txt $TEST_DIR/output.txt || fail "Program output does not match expectation"
  [[ -d $TEST_DIR/expected-result/ ]] && { diff -rub $TEST_DIR/expected-result/ $TEST_DIR/result/ || fail "Produced result does not match expectations"; }
}

if [ $# -eq 0 ]
then
  run_test fractor
  run_test missing-config-file
  run_test rector
else
  for test in $@
  do
    run_test $test
  done
fi

if [ $SUCCESS -eq 0 ]
then
  echo "Test failed"
  exit 1
fi