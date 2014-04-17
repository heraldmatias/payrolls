#!/bin/sh
i=0
echo '' > /tmp/log_messages
while (true)
do
  i=$(($i+1))
  echo 'Procesando tomo #' $i >> /tmp/log_messages
  psql -w -U postgres -d inei_planilla -c "copy (select * from get_planilla_tomo(6, $i) WHERE isnumeric(valo_calc_phi) = true) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/planillas.csv
  if [ $i -gt 420 ]
  then
     break
  fi
done
i=$(($i+1))
echo 'Procesando conceptos' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT codi_conc_tco, codi_oper_ope, codi_cicl_cic, desc_conc_tco, desc_cort_tco, tipo_conc_tco, tipo_calc_tco, secu_calc_tco, flag_asoc_tco, flag_recu_tco, rnta_qnta_tco, cts_cts_tco, codi_conc_onc, codi_enti_ent, cnta_debe_tco, cnta_habe_tco, clas_conc_tco, flag_pago_tco, sede_conc_tco  FROM conceptos WHERE tipo_conc_tco NOT IN ('0', '4')) TO STDOUT WITH CSV DELIMITER '|' QUOTE '\"'" >> /tmp/conceptos.csv
i=$(($i+1))
echo 'Procesando folios' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT codi_folio, num_folio, per_folio, reg_folio, subt_plan_stp, codi_tomo, tipo_plan_tpl, tipo_folio, desc_folio,mes_folio,ano_folio,rango_folio, fec_inicio, fec_final FROM folios WHERE tipo_plan_tpl IS NOT NULL) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/folios.csv
i=$(($i+1))
echo 'Procesando tomos' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT codi_tomo, per_tomo, ano_tomo, folios_tomo, desc_tomo  FROM tomos) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/tomos.csv
i=$(($i+1))
echo 'Procesando tipos de planillas' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT tipo_plan_tpl, desc_tipo_tpl, tarj_inic_tpl, tarj_fina_tpl, cant_peri_tpl, codi_oper_ope, abrev_tipo_tpl FROM tplanilla) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/tplanilla.csv
i=$(($i+1))
echo 'Procesando tipos conceptos por folio' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT cf.id, cf.orden_conc_folio, cf.codi_folio, cf.codi_conc_tco FROM conceptos_folios cf join conceptos c ON cf.codi_conc_tco = c.codi_conc_tco join folios f on cf.codi_folio = f.codi_folio WHERE c.tipo_conc_tco NOT IN ('0', '4') AND f.tipo_plan_tpl IS NOT NULL) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/conceptosfolios.csv
i=$(($i+1))
echo 'Procesando personal' >> /tmp/log_messages
psql -w -U postgres -d inei_planilla -c "COPY (SELECT codi_empl_per, ape_pat_per, ape_mat_per, nom_emp_per, nomb_cort_per, libr_elec_per FROM personal_encontrado) to STDOUT WITH CSV DELIMITER '|' QUOTE '\"';" >> /tmp/personal.csv
tar -czvf /tmp/data.tar.gz /tmp/*.csv
cp /tmp/data.tar.gz /var/www/html/planilla/web/upload/
rm -f /tmp/*.csv