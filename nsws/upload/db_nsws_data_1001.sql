prompt PL/SQL Developer import file
prompt Created on 10 January 2025 by nigad
set feedback off
set define off
prompt Loading FORMDEFINITION...
insert into FORMDEFINITION (formid, settingsid, formname)
values (1, '1', 'P2 Form (Directorate of Sugar)');
prompt 1 records loaded
prompt Loading SECTIONDEFINITION...
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (1, 1, 'Form applied for -', 0);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (2, 1, 'Sugar Mill Details', 0);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (3, 1, 'Cane Crushed', 1);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (4, 1, 'Production of white / refined / Raw Sugar from Domestic sources', 1);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (5, 1, 'Dispatches', 1);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (6, 1, 'Export', 1);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (7, 1, 'Import', 1);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (8, 1, 'Stock of Sugar (In MT)', 1);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (9, 1, 'Packing details of Sugar (In MT)', 1);
insert into SECTIONDEFINITION (sectionid, formid, sectionname, ismultiplefieldresponse)
values (10, 1, 'Cane Dues Data', 1);
prompt 10 records loaded
prompt Loading FIELDDEFINITION...
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (1, 'Sugar Season', 1, null, null, 1, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (2, 'Month', 1, null, null, 2, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (3, 'Name of the Undertaking/Group', 2, null, null, 3, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (4, 'Plant Name', 2, null, null, 4, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (5, 'Plant Code', 2, null, null, 5, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (6, 'State', 2, null, null, 6, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (7, 'Capacity (In TCD for sugar mills/Tons Per Day (TPD) for refineries)', 3, null, null, 7, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (8, 'Cane Crushed - During the Month (MT)', 3, null, null, 8, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (9, 'Select', 4, null, null, 9, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (10, '2(I) White / Refined Sugar', 4, null, 1, 10, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (11, '2(II) Raw Sugar', 4, null, 1, 11, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (12, '2(III) Procured sugar', 4, null, 1, 12, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (13, '3(1) Diversion/sale of B-heavy/Syrup/sugarcane juice/sugar', 4, null, 1, 13, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (14, '3(2) Ethanol Production', 4, null, 1, 14, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (15, '4. Recovery % age', 4, null, 1, 15, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (16, 'Select', 5, null, null, 16, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (17, '6.1.1 Domestic Dispatch w.r.t. monthly release quantity', 5, null, 1, 17, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (18, '6.1.2 Domestic Dispatch w.r.t. additional allotment, if any', 5, null, 1, 18, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (19, '6.1.3 Domestic Dispatch w.r.t. extended quota', 5, null, 1, 19, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (20, '6.1.4 Any other domestic Dispatch', 5, null, 1, 20, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (21, '6.2 BISS Dispatch of unmarketable old Sugar for further processing', 5, null, 1, 21, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (22, '6.3 Internal transfer of raw sugar within a group', 5, 1, 1, 22, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (23, '6.4 Internal transfer of white sugar within a group', 5, 1, 1, 23, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (24, '6.5 Sale of raw sugar to other sugar mills for domestic purpose', 5, 1, 1, 24, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (25, 'HSN code and related details', 5, null, 1, 25, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (26, 'Select', 6, null, null, 26, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (27, '6.6 (a) Export under OGL/Export Quota- (i) White/ refined Sugar', 6, null, 1, 27, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (28, '6.6 (a) Export under OGL- (ii) Raw Sugar (including SEZ refinery)', 6, null, 1, 28, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (29, '6.6 (a) Export under OGL- (iii) Raw Sugar Sold to Refineries for Export by Invoice', 6, null, 1, 29, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (30, '6.6 (b) Export under AAS (White Sugar)', 6, null, 1, 30, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (31, 'Is there any import applicable?', 7, null, null, 31, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (32, '6.7 (a) Import under OGL - (i) White/refined Sugar - Qty Received - During the Month (MT)', 7, null, null, 32, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (33, '6.7 (a) Import under OGL - (ii) Raw Sugar - Qty Received - During the Month (MT)', 7, null, null, 33, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (34, '6.7 (b) Import under AAS - Qty Received - During the Month (MT)', 7, null, null, 34, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (35, 'Factory Premises - White Sugar', 8, null, 1, 35, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (36, 'Factory Premises - BISS / Brown Sugar, If any', 8, null, 1, 36, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (37, 'Factory Premises - Raw Sugar', 8, null, 1, 37, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (38, 'Outside Godown (Duty paid) - White Sugar', 8, null, 1, 38, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (39, 'Outside Godown (Duty paid) - BISS / Brown Sugar, If any', 8, null, 1, 39, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (40, 'Outside Godown (Duty paid) - Raw Sugar', 8, null, 1, 40, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (41, '50 Kg Jute Bag - Qty in MT', 9, null, null, 41, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (42, '100 Kg Jute Bag - Qty in MT', 9, null, null, 42, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (43, '50 Kg PP/HDPE Bag - Qty in MT', 9, null, null, 43, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (44, 'Other Retail Bags (<= 25 Kg and > 100 Kg)/ Loose Sugar - Qty in MT', 9, null, null, 44, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (45, 'Sugar Season - 2023-24', 10, null, 1, 46, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (46, 'Sugar Season - 2022-23', 10, null, 1, 47, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (47, 'Sugar Season - 2021-22', 10, null, 1, 48, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (48, 'Sugar Season - 2020-21', 10, null, 1, 49, 1);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (49, 'Sugar Season - 2019-20', 10, null, 1, 50, 0);
insert into FIELDDEFINITION (fieldid, fieldname, sectionid, ismultiplesubfieldresponse, issubfields, sequencenumber, isactive)
values (50, 'Sugar Season - 2024-25', 10, null, 1, 45, 1);
prompt 50 records loaded
prompt Loading DEFAULTFIELDDATA...

prompt 1 records loaded
prompt Loading SUBFIELDDEFINITION...
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (1, 10, 'a) From Cane - During the Month (MT)', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (2, 10, 'b) From Reprocessing unmarketable old Sugar - During the Month (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (3, 10, 'c) From raw procured from other domestic sugar mills - During the Month (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (4, 10, 'c(1) From transferred white sugar from other domestic sugar mills - During the Month (MT)', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (5, 10, 'c(2) From Raw Sugar used from own Stock - During the Month (MT)', 5);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (6, 10, 'c(3) White sugar produced from own stock of raw sugar - During the Month (MT)', 6);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (7, 11, 'a) From Cane - During the Month (MT)', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (8, 11, 'b) From Reprocessing unmarketable old Sugar - During the Month (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (9, 11, 'c) Raw sugar procured from other domestic sugar mills', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (10, 12, 'a) Raw Sugar - Internal transfer from other group sugar mills - During the Month (MT)', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (11, 12, 'Internal transfer - Plant Code', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (12, 12, 'Internal transfer - Plant Name', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (13, 12, 'b) From Imported Raw Sugar - During the Month (MT)', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (14, 13, '(i).a. Qty of Syrup/Sugarcane Juice/Sugar diverted for ethanol - During the Month (in MT)', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (15, 13, '(ii).a. Qty of B-Heavy diverted for ethanol - During the Month (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (16, 13, '(iii).a. Qty of C-Heavy diverted for ethanol - During the Month (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (17, 13, '(iv) Sale of B-Heavy - During the Month (MT)', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (18, 13, '(v) Sale of Syrup/Sugarcane Juice/Sugar - During the Month (MT)', 5);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (19, 13, '(vi) Sale of C-Heavy - During the Month (MT)', 6);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (20, 14, '(i).b. Ethanol Production from In-house Syrup/Sugarcane Juice/Sugar - During the Month (in KL)', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (21, 14, '(ii).b. Ethanol Production from In-house B-Heavy - During the Month (in KL)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (22, 14, '(iii).b. Ethanol Production from In-house C-Heavy - During the Month (in KL)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (23, 15, '4 (i) Purity of Mixed Juice (Monthly Average) - During Month (in MT)', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (24, 15, '4 (ii) Pol in Mixed Juice % Cane (Monthly Average) - During the Month (in MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (25, 17, 'Release Order - Date', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (26, 17, 'Release order - Qty Released (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (27, 17, 'Qty Dispatched - During the Month (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (28, 17, 'Remarks', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (29, 18, 'Release Order - Date', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (30, 18, 'Release order - Qty Released (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (31, 18, 'Qty Dispatched - During the Month (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (32, 18, 'Remarks', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (33, 19, 'Release Order - Date', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (34, 19, 'Release order - Qty Released (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (35, 19, 'Qty Dispatched - During the Month (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (36, 19, 'Remarks', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (37, 20, 'Release Order - Date', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (38, 20, 'Release order - Qty Released (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (39, 20, 'Qty Dispatched - During the Month (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (40, 20, 'Remarks', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (41, 21, 'Release Order - Date', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (42, 21, 'Qty Used for reprocessing - During the Month (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (43, 22, 'Plant code of sugar mill to which sugar transferred', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (44, 22, 'Qty Transferred to other mills - During the Month (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (45, 23, 'Plant code of sugar mill to which sugar transferred', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (46, 23, 'Qty Transferred to other mills - During the Month (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (47, 24, 'Plant code of sugar mill to which sugar transferred', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (48, 24, 'Qty Transferred to other mills - During the Month (MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (49, 25, 'Total Quantity of Sales (in MT) for HSN Code - 17011490', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (50, 25, 'Total Quantity of Sales (in MT) for HSN Code - 17019990', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (51, 25, 'Total Quantity of Sales (in MT) for HSN Code - Others', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (52, 27, 'Release Order (if applicable) - No.', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (53, 27, 'Release Order (if applicable) - Date', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (54, 27, 'Release Order (if applicable) - Qty released (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (55, 27, 'Qty Dispatched - During the Month (MT)', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (56, 28, 'Release Order (if applicable) - No.', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (57, 28, 'Release Order (if applicable) - Date', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (58, 28, 'Release Order (if applicable) - Qty released (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (59, 28, 'Qty Dispatched - During the Month (MT)', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (60, 29, 'Release Order (if applicable) - No.', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (61, 29, 'Release Order (if applicable) - Date', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (62, 29, 'Qty Dispatched - During the Month (MT)', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (63, 29, 'Name of mill/refinery to whom sold', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (64, 30, 'Export Order (if applicable) - No.', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (65, 30, 'Export Order (if applicable) - Date', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (66, 30, 'Export Order (if applicable) - Qty released', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (67, 30, 'Qty Received - During the Month (MT)', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (68, 35, 'Opening Stock', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (69, 36, 'Opening Stock', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (70, 36, 'Closing Stock', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (71, 37, 'Opening Stock', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (72, 38, 'Opening Stock', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (73, 38, 'Closing Stock', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (74, 39, 'Opening Stock', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (75, 39, 'Closing Stock', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (76, 40, 'Opening Stock', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (77, 40, 'Closing Stock', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (78, 50, 'Cane Price Payable (in Rs Cr) - During the Month', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (79, 50, 'Cane Price Paid (in Rs Cr) - During the Month', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (80, 50, 'No. of farmers from which cane procured', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (81, 46, 'Cane Crushed', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (82, 46, 'Sugar Production (in MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (83, 46, 'Sugar Recovery', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (84, 46, 'Cane Price Payable (in Rs Cr) - During the Sugar Season', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (85, 46, 'Cane Price Paid (in Rs Cr) - During the Month', 5);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (86, 46, 'No. of farmers from which cane procured - During the Month', 6);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (87, 47, 'Cane Crushed', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (88, 47, 'Sugar Production (in MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (89, 47, 'Sugar Recovery', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (90, 47, 'Cane Price Payable (in Rs Cr) - During the Sugar Season', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (91, 47, 'Cane Price Paid (in Rs Cr) - During the Month', 5);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (92, 47, 'No. of farmers from which cane procured', 6);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (93, 48, 'Cane Crushed', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (94, 48, 'Sugar Production (in MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (95, 48, 'Sugar Recovery', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (96, 48, 'Cane Price Payable (in Rs Cr) - During the Sugar Season', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (97, 48, 'Cane Price Paid (in Rs Cr) - During the Month', 5);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (98, 48, 'No. of farmers from which cane procured', 6);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (99, 49, 'Cane Crushed', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (100, 49, 'Sugar Production (in MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (101, 49, 'Sugar Recovery', 3);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (102, 49, 'Cane Price Payable (in Rs Cr) - During the Sugar Season', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (103, 49, 'Cane Price Paid (in Rs Cr) - During the Month', 5);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (104, 49, 'No. of farmers from which cane procured', 6);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (108, 45, 'Cane Price Payable (in Rs Cr) - During the Sugar Season', 4);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (109, 45, 'Cane Price Paid (in Rs Cr) - During the Month', 5);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (110, 45, 'No. of farmers from which cane procured - During the Month', 6);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (105, 45, 'Cane Crushed', 1);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (106, 45, 'Sugar Production (in MT)', 2);
insert into SUBFIELDDEFINITION (subfieldid, fieldid, subfieldname, sequencenumber)
values (107, 45, 'Sugar Recovery', 3);
prompt 110 records loaded
set feedback on
set define on
prompt Done.
