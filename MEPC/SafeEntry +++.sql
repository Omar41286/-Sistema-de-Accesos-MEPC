
USE SafeEntry;

CREATE TABLE Retiros (
    ID_retiro INT PRIMARY KEY IDENTITY(1,1),
    ProductoID INT,
    Cantidad INT,
    Fecha DATETIME,
    ID_usuario INT,
    FOREIGN KEY (ProductoID) REFERENCES Producto(ID_producto),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
);

ALTER TABLE Usuario
ADD imagen_perfil VARCHAR(255) NULL;


SELECT * FROM Producto;
 
SELECT* FROM Almacen;

--Aqui nomas renombre los que tenia mal xD
UPDATE Producto 
SET 
	Nombre_producto='Cemento Portland',
	Descripcion='Saco de cemento Portland tipo I de 50 kg',
	Precio=130
WHERE ID_producto=1;

UPDATE Producto 
SET 
	Nombre_producto='Varilla de Acero',
	Descripcion='Varilla de acero corrugado de 3/8 pulgadas',
	Precio=75
WHERE ID_producto=2;

UPDATE Producto 
SET 
	Nombre_producto='Malla Electrosoldada',
	Descripcion='Malla electrosoldada de 6x6 para losa',
	Precio=1100
WHERE ID_producto=3;

UPDATE Producto 
SET 
	Nombre_producto='Yeso',
	Descripcion='Saco de yeso para acabados de 25 kg',
	Precio=85.50
WHERE ID_producto=4;

UPDATE Producto 
SET 
	Nombre_producto='Tubo Conduit PVC',
	Descripcion='Tubo conduit de PVC de 3/4 pulgadas, para protección de cableado (3 m)',
	Precio=55
WHERE ID_producto=5;

--para poblarla es esto:
INSERT INTO Producto (Nombre_producto, Descripcion, Precio)
VALUES
('Pintura Acrílica', 'Cubeta de pintura acrílica blanca de 19 litros', 850.00),
('Impermeabilizante', 'Cubeta de impermeabilizante para azotea de 19 litros', 1200.00),
('Block de Concreto', 'Block de concreto de 12x20x40 cm', 14.00),
('Cable THW 12', 'Cable eléctrico calibre 12, ideal para instalaciones residenciales (metro)', 15.00),
('Cable THW 10', 'Cable eléctrico calibre 10, para instalaciones de mayor capacidad (metro)', 20.00),
('Clavo de Construcción', 'Paquete de clavos de 4 pulgadas (1 kg)', 55.00),
('Tornillo Autorroscante', 'Paquete de tornillos autorroscantes de 1/4 (100 piezas)', 150.00),
('Carretilla', 'Carretilla metálica con capacidad de 90 litros', 950.00),
('Apagador Sencillo', 'Apagador eléctrico sencillo de 15 amperios', 25.00),
('Contacto Doble', 'Contacto doble con placa de policarbonato', 40.00),
('Centro de Carga', 'Centro de carga monofásico de 12 espacios', 1250.00),
('Foco LED', 'Foco LED de 12 watts, luz blanca', 45.00),
('Nivel de Burbuja', 'Nivel de burbuja de 24 pulgadas', 250.00);

SELECT * FROM Inventario;
SELECT * FROM Producto;


--Para ponerle en cada distinto almacen los productos 
INSERT INTO Inventario(Cantidad_disponible, ID_producto, ID_almacen)
VALUES
(100,2,1),
(110,4,1),
(240,7,1),
(44,3,1),
(25,8,1),
(10,9,1),
(400,14,1),
(1000,10,1),
(1000,10,2),
(1000,7,2),
(1050,11,2),
(400,19,2),
(70,20,2),
(15,15,2),
(1000,5,2),
(300,1,2),
(230,17,2),
(400,14,2),
(600,2,3),
(300,4,3),
(300,1,3),
(150,16,3),
(300,17,3),
(3000,7,3),
(160,9,3),
(300,14,3),
(300,13,3),
(80,18,3),
(700,1,4),
(300,2,4),
(3000,5,4),
(40,18,4),
(1400,19,4),
(80,3,4),
(3000,10,4),
(300,11,4),
(350,12,4),
(45,15,4),
(1500,1,5),
(300,4,5),
(30,3,5),
(500,2,5),
(1500,10,5),
(100,9,5),
(2000,7,5),
(300,12,5),
(100,20,5),
(3000,13,5);