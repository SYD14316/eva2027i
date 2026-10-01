/* Preparacion de valores */  
  UPDATE participantes SET correo='soporteydesarrollo1' WHERE id=8;
/*Bloque 1 - PROFESOR*/
  /*Entrar como elsa padilla*/
    UPDATE participantes SET correo='soporteydesarrollo' WHERE id=2002;
  /*Consulta de las evaluaciones registradas*/
    SELECT * FROM lic_profesores_por_profesores;
  /*Restaurar a elsa padilla*/
    UPDATE participantes SET correo='elsa.padilla' WHERE id=2002;
  /**/
/*Bloque 2 - ALUMNO*/
  /*Entrar como cristian sandoval*/
    UPDATE participantes SET correo='soporteydesarrollo' WHERE id=1002;
  /*Consulta de las evaluaciones registradas*/
    select * from lic_profesores_por_alumnos;
  /*Restaurar a cristian sandoval*/
    UPDATE participantes SET correo='cristian.villarruelsandoval' WHERE id=1002;
  /**/
/*Fin Bloque 2*/
/*Bloque 3 - COORDINADOR*/
  /*Entrar como bety oceguera*/
    UPDATE participantes SET correo='soporteydesarrollo' WHERE id=902;
  /*Consulta de las evaluaciones registradas*/
    select * from lic_profesores_por_coordinadores;
  /*Restaurar a bety oceguera*/
    UPDATE participantes SET correo='jefatura.materiassello' WHERE id=902;
  /**/
/*Restauracion de valores predeterminados*/
  UPDATE participantes SET correo='soporteydesarrollo' WHERE id=8;
  UPDATE participantes SET evaluados='',num_evaluados=0,total_evaluaciones=0,ultimo_acceso='' WHERE id IN (8,1002,2002,902);
  TRUNCATE TABLE lic_profesores_por_alumnos;
  TRUNCATE TABLE lic_profesores_por_profesores;
  TRUNCATE TABLE lic_profesores_por_coordinadores;
/*Revision final de los usuarios utilizados*/
  select * from participantes WHERE id IN (8,1002,2002,902);
/**/
