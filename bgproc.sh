#!/bin/sh
i=0
echo '' > /tmp/log_messages
while (true)
do
  i=$(($i+1))
  echo 'Procesando tomo #' $i >> /tmp/log_messages
  psql -w -U postgres -d planillas -c "copy (select * from get_planilla_tomo($i)) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/data.csv
  sleep 1
  if [ $i -gt 100 ]
  then
     break
  fi
done
