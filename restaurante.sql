/*******************************************************************************
NO MODIFIQUE ESTE FICHERO, NO TENDRÁ EFECTO
********************************************************************************/
/*******************************************************************************
Gestión de restaurante
Description: Creates and populates the DB.
DB Server: Sqlite
Author: Juan Carlos Rodriguez-del-Pino
License: GPL3
********************************************************************************/
PRAGMA foreign_keys = ON;
/*******************************************************************************
   Drop Tables
********************************************************************************/
DROP TABLE IF EXISTS [usuarios];

DROP TABLE IF EXISTS [mesas];

DROP TABLE IF EXISTS [articulos];

DROP TABLE IF EXISTS [comandas];

DROP TABLE IF EXISTS [lineascomanda];

/*******************************************************************************
   Create Tables
********************************************************************************/
CREATE TABLE [usuarios]
(
    [id] INTEGER PRIMARY KEY,
    [usuario] NVARCHAR(20)  NOT NULL,
    [clave] NVARCHAR(32)  NOT NULL,
    [nombre] NVARCHAR(200),
    [tipo] INTEGER
);
/*
tipo = 1 => camarero
tipo = 2 => cocinero
*/
CREATE UNIQUE INDEX IF NOT EXISTS [indexusuario] on [usuarios] ([usuario]);

CREATE TABLE [mesas]
(
    [id] INTEGER PRIMARY KEY,
    [nombre] NVARCHAR(60)
);

CREATE TABLE [articulos]
(
    [id] INTEGER PRIMARY KEY,
    [nombre] NVARCHAR(60),
    [tipo] INTEGER,
    [stock] INTEGER,
    [PVP] DECIMAL(10,2)
);
/*
tipo = 0 => no necesita preparación
tipo = 1 => necesita preparación de cocinero
*/

CREATE TABLE [comandas]
(
    [id] INTEGER PRIMARY KEY,
    [mesa] INTEGER NOT NULL,
    [camareroapertura] INTEGER NOT NULL,
    [horaapertura] INTEGER NOT NULL,
    [camarerocierre] INTEGER,
    [horacierre] INTEGER DEFAULT 0,
    [PVP] INTEGER DEFAULT 0,
    CONSTRAINT 'unico' UNIQUE ([mesa],[horacierre]),
    FOREIGN KEY ([mesa]) REFERENCES [mesas] ([id])
);
/*
horacierre = 0 => comanda abierta
horacierre > 0 => comanda cerrada
*/

CREATE UNIQUE INDEX IF NOT EXISTS [indexcommandas] on [comandas] ([mesa], [horacierre]);
CREATE TABLE [lineascomanda]
(
    [id] INTEGER PRIMARY KEY,
    [comanda] INTEGER  NOT NULL,
    [articulo] INTEGER  NOT NULL,
    [camareropeticion] INTEGER  NOT NULL,
    [horapeticion] INTEGER  NOT NULL,
    [tipo] INTEGER,
    [cocinero] INTEGER,
    [horainicio] INTEGER DEFAULT 0,
    [horafinalizacion] INTEGER DEFAULT 0,
    [camareroservicio] INTEGER,
    [horaservicio] INTEGER DEFAULT 0,
    FOREIGN KEY ([comanda]) REFERENCES [comandas] ([id]) 
		ON DELETE CASCADE
);
/*
tipo = 0 => no necesita preparación
tipo = 1 => necesita preparación de cocinero
Según tipo:
tipo = 0 y horaservicio = 0 => Sin servir
tipo = 0 y horaservicio > 0 => Ya servida
tipo = 1 y horainicio = 0 => Necesita elaboración
tipo = 1 y horainicio > 0 y horafinalizacion = 0 => En elaboración
tipo = 1 y horainicio > 0 y horafinalizacion > 0 y horaservicio = 0 => Elaboración sin servir
tipo = 1 y horaservicio > 0 => Ya servida
*/
CREATE UNIQUE INDEX IF NOT EXISTS [indexlineascomanda] on [lineascomanda] ([comanda],[id]);


/*******************************************************************************
   Populate Tables
********************************************************************************/
INSERT INTO [usuarios] ([usuario], [clave], [nombre], [tipo]) VALUES ('cama1', '1eaa8bb195869a23f081acbb5bf08527', 'Fulano de Tal', 1);
INSERT INTO [usuarios] ([usuario], [clave], [nombre], [tipo]) VALUES ('cama2', '0196f6c4f97df3f48d570c23e46501ae', 'Mengano de Cual', 1);
INSERT INTO [usuarios] ([usuario], [clave], [nombre], [tipo]) VALUES ('coci1', '1798c7d9bd5a5d637ead47154f0d36e3', 'Zutano de Sal', 2);
INSERT INTO [usuarios] ([usuario], [clave], [nombre], [tipo]) VALUES ('coci2', '2b556258d4e0a9fe879765d2b76dd039', 'Perengano de Na', 2);

INSERT INTO [mesas] ([nombre]) VALUES ('Mesa interior 1');
INSERT INTO [mesas] ([nombre]) VALUES ('Mesa interior 2');
INSERT INTO [mesas] ([nombre]) VALUES ('Mesa interior 3');
INSERT INTO [mesas] ([nombre]) VALUES ('Mesa interior 4');
INSERT INTO [mesas] ([nombre]) VALUES ('Mesa terraza 1');
INSERT INTO [mesas] ([nombre]) VALUES ('Mesa terraza 2');

INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Agua artificial', 0, 120, 1.05);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Poca Cola', 0, 100, 1.85);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Falta Naranja', 0, 100, 1.75);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Six Up', 0, 100, 1.60);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Cerveza Subtropical', 0, 100, 1.90);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Vino Pinto', 0, 100, 5.35);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Vino Azul', 0, 100, 10.75);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Pan', 0, 90, 0.55);

INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Endulzada Mixta', 1, 100, 4.25);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Papas lisas', 1, 100, 3.75);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Ropa nueva', 1, 100, 4.75);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Concretas de pollo', 1, 100, 5.75);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Solomaiz a la pimienta', 1, 100, 11.75);

INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Helado Tafira Invernal', 0, 100, 2.75);
INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Dresas con Nata', 0, 100, 2.50);

INSERT INTO [articulos] ([nombre], [tipo], [stock], [PVP]) VALUES ('Helado Tafira Invernal', 0, 100, 2.75);

/*******************************************************************************
   Datos iniciales
********************************************************************************/
INSERT INTO [comandas] ([mesa], [camareroapertura], [horaapertura]) VALUES (1, 2, 1456950207);
INSERT INTO [lineascomanda] ([comanda], [articulo], [camareropeticion], [horapeticion], [tipo]) VALUES (1, 1, 2, 1456950217, 0);
INSERT INTO [lineascomanda] ([comanda], [articulo], [camareropeticion], [horapeticion], [tipo]) VALUES (1, 2, 2, 1456950217, 0);
INSERT INTO [lineascomanda] ([comanda], [articulo], [camareropeticion], [horapeticion], [tipo]) VALUES (1, 3, 2, 1456950217, 0);
INSERT INTO [lineascomanda] ([comanda], [articulo], [camareropeticion], [horapeticion], [tipo], [cocinero], [horainicio]) VALUES (1, 11, 2, 1456950217, 1, 3, 1456950317);
INSERT INTO [lineascomanda] ([comanda], [articulo], [camareropeticion], [horapeticion], [tipo], [cocinero], [horainicio], [horafinalizacion]) VALUES (1, 13, 2, 1456950217, 1, 3, 1456950327, 1456950927);

INSERT INTO [comandas] ([mesa], [camareroapertura], [horaapertura]) VALUES (2, 2, 1456949427);
INSERT INTO [lineascomanda] ([comanda], [articulo], [camareropeticion], [horapeticion], [tipo]) VALUES (2, 1, 1, 1456950417, 0);
INSERT INTO [lineascomanda] ([comanda], [articulo], [camareropeticion], [horapeticion], [tipo]) VALUES (2, 2, 1, 1456950417, 0);
INSERT INTO [lineascomanda] ([comanda], [articulo], [camareropeticion], [horapeticion], [tipo]) VALUES (2, 13, 1, 1456950417, 1);

