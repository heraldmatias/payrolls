#!/bin/sh
i=0
echo '' > /tmp/log_messages
while (true)
do
  i=$(($i+1))
  echo 'Procesando tomo #' $i >> /tmp/log_messages
  psql -w -U postgres -d planillas -c "copy (select * from get_planilla_tomo(6, $i)) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/planillas.csv
  if [ $i -gt 420 ]
  then
     break
  fi
done
i=$(($i+1))
echo 'Procesando conceptos' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT codi_conc_tco, codi_oper_ope, codi_cicl_cic, desc_conc_tco, desc_cort_tco, tipo_conc_tco, tipo_calc_tco, secu_calc_tco, flag_asoc_tco, flag_recu_tco, rnta_qnta_tco, cts_cts_tco, codi_conc_onc, codi_enti_ent, cnta_debe_tco, cnta_habe_tco, clas_conc_tco, flag_pago_tco, sede_conc_tco  FROM conceptos WHERE codi_conc_tco NOT IN ('C726', 'C706', 'C656', 'C531', 'C496', 'C497','C498','C499','C500','C501','C317','C316','C318','C363','C362','C364','C54','C416','C400','C69','C80','C2','C107')) TO STDOUT WITH CSV DELIMITER '|' QUOTE '\"'" >> /tmp/conceptos.csv
i=$(($i+1))
echo 'Procesando folios' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT codi_folio, num_folio, per_folio, reg_folio, subt_plan_stp, codi_tomo, tipo_plan_tpl, tipo_folio FROM folios) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/folios.csv
i=$(($i+1))
echo 'Procesando tomos' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT codi_tomo, per_tomo, ano_tomo, folios_tomo, desc_tomo  FROM tomos) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/tomos.csv
i=$(($i+1))
echo 'Procesando tipos de planillas' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT tipo_plan_tpl, desc_tipo_tpl, tarj_inic_tpl, tarj_fina_tpl, cant_peri_tpl, codi_oper_ope, abrev_tipo_tpl FROM tplanilla) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/tplanilla.csv
i=$(($i+1))
echo 'Procesando tipos conceptos por folio' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT id, orden_conc_folio, codi_folio, codi_conc_tco  FROM conceptos_folios WHERE codi_conc_tco NOT IN ('C726', 'C706', 'C656', 'C531', 'C496', 'C497','C498','C499','C500','C501','C317','C316','C318','C363','C362','C364','C54','C416','C400','C69','C80','C2','C107')) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/conceptosfolios.csv
tar -czvf /tmp/data.tar.gz /tmp/*.csv
cp /tmp/data.tar.gz /var/www/html/planilla/web/upload/
rm -f /tmp/*.csv