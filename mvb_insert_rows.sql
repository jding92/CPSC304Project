alter session set NLS_DATE_FORMAT='DD-MON-YYYY';
alter session set NLS_TIMESTAMP_FORMAT='HH24:MI:SS';

commit;


insert into users values
(1, 'GYao', '16381139', 'ginayao1995@hotmail.com', 1000.00, null, null);

insert into users values 
(2, 'LShao', '32457137', 'yiwenlilyshao@gmail.com', 0.00, null, null);

insert into users values
(3, 'PTee', '57599136', 'patrick.455@hotmail.com', 100.00, null, null);

insert into users values
(4, 'JDing', '48540158', 'jackding92@alumni.ubc.ca', 100000.00, null, null);

insert into users values
(5, 'BZhang', 'tzuyu', 'beiweizhang@gmail.com', 0.25, null, null);

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
(4,5);

insert into market_item values
(5,5);

insert into listing values
(1, 1, '10-JAN-2018', 10.00, 2);

insert into listing values
(2, 2, '10-JAN-2018', 100.00, 1);

insert into listing values
(3, 3, '11-JAN-2018', 20.00, 4);

insert into listing values
(4, 5, '12-JAN-2018', 0.55, 2);

insert into listing values
(5, 5, '13-JAN-2018', 1.00, 12);

insert into game values
(1, 'Skyrim', '23:59:59');

insert into game values
(2, 'CS:GO', '10:50:50');

insert into game values 
(3, 'DOTA 2', '03:00:00');

insert into game values
(4, 'Pubg', '00:00:22');

insert into game values
(5, 'Stardew Valley', '10:00:00');

insert into item_belongsTo values
(1, 1, 'CS:GO Base Grade Container', 'Clutch Case', 2);

insert into item_belongsTo values
(2, 1, 'This key only opens Clutch cases', 'Clutch Case Key', 2);

insert into item_belongsTo values
(3, 1, 'Before the age of Gaster, even the most adept of mages would turn to flee with a burst of defensive magic when facing the close-quarters charge of a well-armed opponent.', 'Magus Accord', 3);

insert into item_belongsTo values
(4, 1, 'At all times the Anti-Mage feels the flames of defining loss burning against his back, pushing him ever forward in pursuit of his blood-sworn oath', 'Origins of Faith', 3);

insert into item_belongsTo values
(5, 1, 'Biker Crate', 'Biker Crate', 4);

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
