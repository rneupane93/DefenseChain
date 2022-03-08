#!/bin/bash
#
# SPDX-License-Identifier: Apache-2.0

function _exit(){
    printf "Exiting:%s\n" "$1"
    exit -1
}

# Exit on first error, print all commands.
set -ev
set -o pipefail

# Where am I?
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

#Clean up old identity
rm -rf ${DIR}/wallet/wallet/*

export FABRIC_CFG_PATH="${DIR}/../config"

cd "${DIR}/../test-network/"

docker kill cliDigiBank cliMagnetoCorp logspout || true
./network.sh down
./network.sh up createChannel -ca -s couchdb
./network.sh deployCC -ccn claimchain -ccp ../ClaimChain/contract-go -ccl go

# Copy the connection profiles so they are in the correct organizations.
cp "${DIR}/../test-network/organizations/peerOrganizations/org1.example.com/connection-org1.yaml" "${DIR}/organization/shelter/gateway/"
cp "${DIR}/../test-network/organizations/peerOrganizations/org2.example.com/connection-org2.yaml" "${DIR}/organization/statefarm/gateway/"
cp "${DIR}/../test-network/organizations/peerOrganizations/org3.example.com/connection-org3.yaml" "${DIR}/organization/allstate/gateway/"

cp "${DIR}/../test-network/organizations/peerOrganizations/org1.example.com/users/User1@org1.example.com/msp/signcerts/"* "${DIR}/../test-network/organizations/peerOrganizations/org1.example.com/users/User1@org1.example.com/msp/signcerts/User1@org1.example.com-cert.pem"
cp "${DIR}/../test-network/organizations/peerOrganizations/org1.example.com/users/User1@org1.example.com/msp/keystore/"* "${DIR}/../test-network/organizations/peerOrganizations/org1.example.com/users/User1@org1.example.com/msp/keystore/priv_sk"

cp "${DIR}/../test-network/organizations/peerOrganizations/org2.example.com/users/User1@org2.example.com/msp/signcerts/"* "${DIR}/../test-network/organizations/peerOrganizations/org2.example.com/users/User1@org2.example.com/msp/signcerts/User1@org2.example.com-cert.pem"
cp "${DIR}/../test-network/organizations/peerOrganizations/org2.example.com/users/User1@org2.example.com/msp/keystore/"* "${DIR}/../test-network/organizations/peerOrganizations/org2.example.com/users/User1@org2.example.com/msp/keystore/priv_sk"

cp "${DIR}/../test-network/organizations/peerOrganizations/org3.example.com/users/User1@org3.example.com/msp/signcerts/"* "${DIR}/../test-network/organizations/peerOrganizations/org3.example.com/users/User1@org3.example.com/msp/signcerts/User1@org3.example.com-cert.pem"
cp "${DIR}/../test-network/organizations/peerOrganizations/org3.example.com/users/User1@org3.example.com/msp/keystore/"* "${DIR}/../test-network/organizations/peerOrganizations/org3.example.com/users/User1@org3.example.com/msp/keystore/priv_sk"

echo Suggest that you monitor the docker containers by running
echo "./organization/magnetocorp/configuration/cli/monitordocker.sh fabric_test"

cp -r "${DIR}/../test-network/organizations/peerOrganizations" "${DIR}/../explorer/organizations/"
cp -r "${DIR}/../test-network/organizations/ordererOrganizations" "${DIR}/../explorer/organizations/"

