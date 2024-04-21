#!/usr/bin/env bash

set -x

TESTS_BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd)"
BASE_DIR="$TESTS_BASE_DIR/../"

cd $TESTS_BASE_DIR

rm -r composer.lock vendor || true
composer install

run_test() {
  TEST_DIR=$1

  cd $TESTS_BASE_DIR/$TEST_DIR

  [[ -d ./output/ ]] && rm -rf ./output/
  cp -r fixtures/ output/

  cd $TESTS_BASE_DIR
  ./vendor/bin/lifter run --file=$TESTS_BASE_DIR/$TEST_DIR/lifter.php

  diff -rub $TEST_DIR/expected-output/ $TEST_DIR/output/
}

run_test rector