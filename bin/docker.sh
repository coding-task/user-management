#!/bin/bash
COMPOSE_FILE="docker/docker-compose.yml"

docker-compose -p um-api -f ${COMPOSE_FILE} down
docker-compose -p um-api -f ${COMPOSE_FILE} up --build --remove-orphans -d

