#!/usr/bin/env bash

set -v

DEPLOYMENT_ENVIRONMENT=${1:-production}

# Only execute deployment if $TRAVIS is null (we are not on CI) or we are on CI merging to master and the build has ended successfully
if [[ -z "${TRAVIS}" ]] || [[ "${TRAVIS_PULL_REQUEST}" == "false" && "${TRAVIS_TEST_RESULT}" == 0 && "${TRAVIS_BRANCH}" == 'master' ]]; then
  docker run --rm -it \
    -v /var/run/docker.sock:/var/run/docker.sock \
    -v "$PWD:/app" \
    -e AWS_ACCESS_KEY_ID \
    -e AWS_SECRET_ACCESS_KEY \
    fiunchinho/wimpy /app/deploy.yml  \
      --extra-vars "wimpy_release_version=${TRAVIS_COMMIT} wimpy_deployment_environment=${DEPLOYMENT_ENVIRONMENT}" -vv
fi
