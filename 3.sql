alter session set NLS_DATE_FORMAT='DD-MON-YYYY';
alter session set NLS_TIMESTAMP_FORMAT='HH24:MI:SS';

commit;


insert into users values
(1, 'GYao', '16381139', 'ginayao1995@hotmail.com', 1000.00, null);

insert into users values 
(2, 'LShao', '32457137', 'yiwenlilyshao@gmail.com', 0.00, null);

insert into users values
(3, 'PTee', '57599136', 'patrick.455@hotmail.com', 100.00, null);

insert into users values
(4, 'JDing', '48540158', 'jackding92@alumni.ubc.ca', 100000.00, null);

insert into users values
(5, 'RagingBob', 'tzuyu', 'beiweizhang@gmail.com', 0.25, null);

insert into administrator values
(1);

insert into administrator values
(4);

insert into billing_info values
('1111222233334444', '0118', '123', 'Gina Yao', '123 Fake Street, Vancouver, BC', '6041234567', 1);

insert into billing_info values
('2222333344445555', '0219', '234', 'Lily Shao', '456 Fake Street, Vancouver, BC', '6041112222', 2);

insert into billing_info values
('3333444455556666', '0320', '345', 'Patrick Tee', '789 Fake Street, Vancouver, BC', '6043334444', 3);

insert into billing_info values
('4444555566667777', '0421', '456', 'Jack Ding', '111 Fake Street, Vancouver, BC', '6045556666', 4);

insert into billing_info values
('5555666677778888', '0522', '567', 'Bob Zhang', '222 Fake Street, Vancouver, BC', '6047778888', 5);

insert into market_item values
(1,1);

insert into market_item values
(2,2);

insert into market_item values
(3,3);

insert into market_item values
(33,4);

insert into market_item values
(4,4);

insert into market_item values
(11,4);

insert into market_item values
(7,4);

insert into market_item values
(44,5);

insert into market_item values
(5,5);

insert into market_item values
(6,4);

insert into market_item values
(20,5);

insert into market_item values
(300,4);

insert into market_item values
(301,4);

insert into market_item values
(400,4);

insert into market_item values
(401,4);

insert into market_item values
(200,2);

insert into market_item values
(201,2);

insert into listing values
(1, 200, 1, '10-JAN-2018', 10.00, 2);

insert into listing values
(2, 201, 2, '10-JAN-2018', 100.00, 1);

insert into listing values
(3, 300, 3, '11-JAN-2018', 20.00, 4);

insert into listing values
(4, 301, 5, '12-JAN-2018', 0.55, 2);

insert into listing values
(5, 400, 5, '13-JAN-2018', 1.00, 12);

insert into game values
(1, 'Skyrim', '12-MAR-2012');

insert into game values
(11, 'Skyrim', '30-APR-2011');

insert into game values
(2, 'CS:GO', '10-JAN-2014');

insert into game values 
(3, 'DOTA 2', '28-FEB-2013');

insert into game values 
(33, 'DOTA 2', '04-MAY-2014');

insert into game values
(4, 'PLAYERUNKNOWN''S BATTLEGROUNDS', '30-DEC-2016');

insert into game values
(44, 'PLAYERUNKNOWN''S BATTLEGROUNDS', '12-OCT-2017');

insert into game values
(5, 'Stardew Valley', '29-MAY-2015');

insert into game values
(6, 'Fortnite', '30-JAN-2018');

insert into game values
(7, 'Civilization V', '12-JUL-2012');

insert into game values
(20, 'Hatoful Boyfriend', '10-SEP-2011');

insert into item_belongsTo values
(200, 1, 'CS:GO Base Grade Container', 'Clutch Case', 2);

insert into item_belongsTo values
(201, 1, 'This key only opens Clutch cases', 'Clutch Case Key', 2);

insert into item_belongsTo values
(300, 1, 'Before the age of Gaster, even the most adept of mages would turn to flee with a burst of defensive magic when facing the close-quarters charge of a well-armed opponent.', 'Magus Accord', 3);

insert into item_belongsTo values
(301, 1, 'At all times the Anti-Mage feels the flames of defining loss burning against his back, pushing him ever forward in pursuit of his blood-sworn oath', 'Origins of Faith', 3);

insert into item_belongsTo values
(400, 2, 'A rare crate that contains a cosmetic skin for a BATTLEGROUNDS gun', 'Biker Crate', 4);

insert into item_belongsTo values
(401, 5, 'A crate that contains a cosmetic skin for a BATTLEGROUNDS gun', 'Triumph Crate', 4);

insert into transaction_supervises values
(1, '10-JAN-2018', 100.00, '5555666677778888', 5, 2, 2, 1);

insert into transaction_supervises values
(2, '13-JAN-2018', 20.00, '5555666677778888', 5, 3, 3, 1);

insert into transaction_supervises values
(3, '14-JAN-2018', 1.00, '2222333344445555', 2, 5, 5, 4);

insert into transaction_supervises values
(4, '15-JAN-2018', 0.55, '2222333344445555', 2, 5, 4, 4);

insert into transaction_supervises values
(5, '16-JAN-2018', 10.00, '3333444455556666', 3, 1, 1, 4);

insert into transaction_supervises values
(6, '30-MAY-2017', 50.00, '1818181818181818', 4, 1, 1, 1);

insert into transaction_supervises values
(7, '01-APR-2018', 10.00, '1818181818181818', 4, 3, 4, 1);

insert into transaction_supervises values
(8, '04-JUN-2016', 300.00, '1818181818181818', 4, 2, 3, 1);

insert into transaction_supervises values
(9, '13-JUN-2017', 39.99, '1818181818181818', 4, 3, 2, 1);

insert into transaction_supervises values
(10, '01-JAN-2018', 1000.00, '1818181818181818', 3, 4, 1, 1);

insert into transaction_supervises values
(11, '31-OCT-2016', 140.50, '1818181818181818', 1, 4, 4, 1);

insert into transaction_supervises values
(12, '13-FEB-2018', 40.20, '1818181818181818', 1, 4, 3, 1);

insert into monitors values
(1,2,3);

insert into monitors values
(4,1,1);

insert into monitors values
(4,3,3);

insert into monitors values
(4,5,4);

insert into monitors values
(4,5,5);


commit work;
