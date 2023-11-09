USE gotruck;

CREATE TABLE personas(
    id bigint unsigned primary key,
    nombre varchar(50) not null,
    apellido varchar(50) not null,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime, -- por laravel
	foreign key (id) references users (id) -- la tabla de users sale de laravel
);

CREATE TABLE gerentes(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references personas(id)
);

CREATE TABLE administradores(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references personas(id)
);

CREATE TABLE conductores(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references personas(id)
);

CREATE TABLE funcionarios(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references personas(id)
);

CREATE VIEW personas_roles AS
    SELECT id, "gerente" rol from gerentes
    UNION
    SELECT id, "administrador" rol from administradores
    UNION
    SELECT id, "funcionario" rol from funcionarios
    UNION
    SELECT id, "conductor" rol from conductores;
    
CREATE TABLE alojamientos(
    id serial primary key,
    direccion varchar(100) unique not null,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime-- por laravel
);

CREATE TABLE almacenes(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references alojamientos(id)
);

CREATE TABLE sedes(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references alojamientos(id)
);

CREATE VIEW alojamientos_tipos AS
    SELECT id, "sede" tipo from sedes
    UNION
    SELECT id, "almacén" tipo from almacenes;
    
CREATE TABLE paquetes(
    id serial primary key,
    peso_en_kg smallint not null,
    email varchar(70) not null,
    destino bigint unsigned not null,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (destino) references sedes(id)
);

CREATE TABLE lotes(
    id serial primary key,
    destino bigint unsigned not null,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (destino) references sedes(id)
);

CREATE TABLE lote_formado_por(
	id_paquete bigint unsigned primary key,
    id_lote bigint unsigned not null,
    updated_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id_lote) references lotes(id),
    foreign key (id_paquete) references paquetes(id)
);

CREATE TABLE vehiculos(
	id serial primary key,
	capacidad_en_toneladas smallint not null,
	updated_at datetime,-- por laravel
    created_at datetime,-- por laravel
    deleted_at datetime-- por laravel
);

CREATE TABLE camiones(
	id_vehiculo bigint unsigned primary key,
	foreign key (id_vehiculo) references vehiculos(id)
);

CREATE TABLE conductor_maneja(
	id_conductor bigint unsigned primary key,
    id_vehiculo bigint unsigned not null unique,
    foreign key (id_vehiculo) references vehiculos(id),
    foreign key (id_conductor) references conductores(id)
);

CREATE TABLE pickups(
	id_vehiculo bigint unsigned primary key,
	foreign key (id_vehiculo) references vehiculos(id)
);

CREATE TABLE lote_asignado_a_camion(
	id_lote bigint unsigned primary key,
    id_camion bigint unsigned not null,
	updated_at datetime,-- por laravel
    created_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    foreign key (id_lote) references lotes(id),
    foreign key (id_camion) references camiones(id_vehiculo)
);

CREATE TABLE paquete_asignado_a_pickup(
	id_paquete bigint unsigned primary key,
    id_pickup bigint unsigned not null,
	updated_at datetime,-- por laravel
    created_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    foreign key (id_paquete) references paquetes(id),
    foreign key (id_pickup) references pickups(id_vehiculo)
);

CREATE TABLE traducciones(
	id bigint,
    texto varchar(1000) not null,
    idioma enum('es', 'en'),
    primary key (id, idioma)
);

/* TITULOS */
INSERT INTO traducciones VALUES (1, "GoTruck - Iniciar sesión", "es");
INSERT INTO traducciones VALUES (1, "GoTruck - Log in", "en");

/* HEADER */
INSERT INTO traducciones VALUES (100, "Cerrar sesión", "es");
INSERT INTO traducciones VALUES (100, "Sign out", "en");

/* FOOTER */
INSERT INTO traducciones VALUES (200, "Política y privacidad", "es");
INSERT INTO traducciones VALUES (200, "Policy and privacy", "en");
INSERT INTO traducciones VALUES (201, "Contacto", "es");
INSERT INTO traducciones VALUES (201, "Contact", "en");
INSERT INTO traducciones VALUES (202, "Todos los derechos reservados", "es");
INSERT INTO traducciones VALUES (202, "All rights reserved", "en");

/* PÁGINA DE INICIO DE SESIÓN */
INSERT INTO traducciones VALUES (300, "Iniciar sesión", "es");
INSERT INTO traducciones VALUES (300, "Log in", "en");
INSERT INTO traducciones VALUES (301, "Datos para iniciar sesión", "es");
INSERT INTO traducciones VALUES (301, "Login information", "en");
INSERT INTO traducciones VALUES (302, "Inicie sesión para acceder al sitio", "es");
INSERT INTO traducciones VALUES (302, "Log in to access the site", "en");
INSERT INTO traducciones VALUES (303, "Contraseña", "es");
INSERT INTO traducciones VALUES (303, "Password", "en");
INSERT INTO traducciones VALUES (304, "Ha ocurrido un error. Revise los campos por favor.", "es");
INSERT INTO traducciones VALUES (304, "An error has ocurred. Please check your input.", "en");

/* PÁGINA PRINCIPAL */
INSERT INTO traducciones VALUES (400, "Aplicaciones", "es");
INSERT INTO traducciones VALUES (400, "Applications", "en");
INSERT INTO traducciones VALUES (401, "Seguimiento", "es");
INSERT INTO traducciones VALUES (401, "Tracking", "en");
INSERT INTO traducciones VALUES (402, "Ve el estado de una entrega buscándola por su id.", "es");
INSERT INTO traducciones VALUES (402, "View the status of a delivery by searching its id.", "en");
INSERT INTO traducciones VALUES (403, "Choferes", "es");
INSERT INTO traducciones VALUES (403, "Drivers", "en");
INSERT INTO traducciones VALUES (404, "Visualiza las entregas pendientes y el trayecto hacia la sede más cercana.", "es");
INSERT INTO traducciones VALUES (404, "View pending deliveries and the route to the nearest location.", "en");
INSERT INTO traducciones VALUES (405, "Almacén", "es");
INSERT INTO traducciones VALUES (405, "Warehouse", "en");
INSERT INTO traducciones VALUES (406, "Gestiona lotes con sus paquetes y asígnalos a un camión para ser entregados.", "es");
INSERT INTO traducciones VALUES (406, "Manage batches with your packages and assign them to a truck for delivery.", "en");
INSERT INTO traducciones VALUES (407, "Administración", "es");
INSERT INTO traducciones VALUES (407, "Administration", "en");
INSERT INTO traducciones VALUES (408, "Adéntrese en el mundo corporativo y gestione su equipo de trabajo.", "es");
INSERT INTO traducciones VALUES (408, "Join the corporate world and manage your team.", "en");

CREATE VIEW vehiculos_tipos AS
    SELECT id_vehiculo, "camión" tipo from camiones
    UNION
    SELECT id_vehiculo, "pickup" tipo from pickups;