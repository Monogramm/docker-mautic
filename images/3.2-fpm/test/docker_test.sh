#!/usr/bin/sh

set -e

################################################################################
# Testing docker containers

echo "Waiting to ensure everything is fully ready for the tests..."
sleep 240

echo "Checking main containers are reachable..."
if ! ping -c 10 -q "${DOCKER_TEST_CONTAINER}" ; then
    echo 'Main container is not responding!'
    #echo 'Check the following logs for details:'
    #tail -n 100 logs/*.log
    exit 1
fi


################################################################################
# Success
echo 'Docker tests successful'


################################################################################
# Automated Unit tests
# https://docs.docker.com/docker-hub/builds/automated-testing/
################################################################################

# TODO Check login page
#HTTP_CODE=$(curl -s -o /dev/null -I -w "%{http_code}" "http://${DOCKER_WEB_CONTAINER}/s/login")
#
#if echo "$HTTP_CODE" | grep -q 302; then
#    echo 'Mautic successufully redirected login page to install'
#else
#    echo "Mautic is not responding: received code $HTTP_CODE"
#    exit 1
#fi


################################################################################
# Success
echo "Docker app '${DOCKER_TEST_CONTAINER}' tests finished"
echo 'Check the CI reports and logs for details.'
exit 0
