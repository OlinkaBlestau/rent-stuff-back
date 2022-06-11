#!/bin/bash
now=$(date '+%Y-%m-%d_%H:%M:%S')
docker exec -t rent-stuff-db pg_dumpall -c -U root  > "backups/backup_${now}.sql"
