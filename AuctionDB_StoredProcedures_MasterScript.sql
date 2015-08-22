DELIMITER $$

CREATE PROCEDURE Bid_Delete(
IN bId INT(8)
)
BEGIN
UPDATE 
Bid B
SET 
B.`Status` = 1
WHERE
B.Id = bId;
END$$

CREATE PROCEDURE Bid_Insert(
IN listingId INT(8),
IN biduserId INT(8),
IN bidVal DECIMAL(8,2),
IN stat INT(1),
OUT id INT(8)
)
BEGIN
INSERT INTO Bid
(ListingId, BiddingUserId, BidValue, `Status`)
VALUES
(listingId, biduserId, bidVal, stat);

SELECT LAST_INSERT_ID() INTO id;
END $$

CREATE PROCEDURE Bid_LoadBidsByListing(
IN listingId INT(8)
)
BEGIN
SELECT B.*
FROM Bid B
WHERE B.ListingId = listingId AND B.`Status` = 0;	

END$$

CREATE PROCEDURE Bid_LoadListingsBidsByUser(
IN userId INT
)
BEGIN

SELECT
B.Id AS BidId,
L.Id AS ListingId,
L.EndDate AS ListingEndDate,
I.`Name` AS ItemName,
B.`Status` AS BidStatus,
B.BidValue
FROM
Bid B
LEFT JOIN Listing L ON B.ListingId = L.Id
LEFT JOIN Item I ON L.ItemId = I.Id
WHERE
B.BiddingUserId = userId
ORDER BY 
L.EndDate ASC,
B.BidValue DESC;

END $$

CREATE PROCEDURE Category_GetAll()
BEGIN
SELECT
C.Id,
C.CategoryName,
C.CategoryDescription
FROM
Category C
ORDER BY
C.CategoryName;
END$$


CREATE PROCEDURE Category_Insert(
IN catName VARCHAR(60),
IN catDesc VARCHAR(255),
OUT id INT(8)
)
BEGIN
INSERT INTO Category
(CategoryName, CategoryDescription)
VALUES
(catName, catDesc);

SELECT LAST_INSERT_ID() INTO id;
END$$

CREATE PROCEDURE Category_Load(
IN catId int(8))
BEGIN
SELECT
C.Id,
C.CategoryName,
C.CategoryDescription
FROM
Category C
WHERE
C.Id = catId;
END$$


CREATE PROCEDURE Item_Insert(
IN `name` VARCHAR(100),
IN longDesc BLOB,
IN catId INT(8),
OUT id INT(8),
OUT createdDate DATETIME
)
BEGIN
DECLARE insDT DATETIME;
SET insDT = NOW();

INSERT INTO Item
(DateCreated, `Name`, LongDescription, CategoryId)
VALUES
(insDT, `name`, longDesc, catId);

SELECT LAST_INSERT_ID() INTO id;
SELECT insDT INTO createdDate;
END$$


CREATE PROCEDURE Item_Load(
IN userId int(4))
BEGIN
SELECT
I.Id,
I.DateCreated,
I.`Name`,
I.LongDescription,
C.Id AS CatId,
C.CategoryName AS CatName,
C.CategoryDescription AS CatDesc
FROM
Item I JOIN Category C ON I.CategoryId = C.Id
WHERE
I.Id = userId;
END$$

CREATE PROCEDURE Item_Update(
IN id INT(8),
IN `name` VARCHAR(100),
IN longDesc BLOB,
IN catId INT(8)
)
BEGIN

UPDATE 
Item I
SET
I.`Name` = `name`,
I.LongDescription = longDesc,
I.CategoryId = catId
WHERE
I.Id = id;
END$$

CREATE PROCEDURE Listing_GetById(
IN listingId INT(8)
)
BEGIN
SELECT L.*
FROM Listing L
WHERE L.Id = listingId
LIMIT 1;
END $$

CREATE PROCEDURE Listing_GetByUserId(
IN userId INT(8),
IN statusInt INT(2)
)
BEGIN

DECLARE now DATETIME;
SET now = NOW();

IF statusInt = 3 THEN
SELECT L.*
FROM Listing L
WHERE L.UserId = userId
ORDER BY L.ListedDate DESC;
ELSEIF statusInt = 2 THEN
SELECT L.*
FROM Listing L
WHERE L.UserId = userId AND L.ListedDate > now
ORDER BY L.ListedDate DESC;
ELSEIF statusInt = 1 THEN
SELECT L.*
FROM Listing L
WHERE L.UserId = userId AND L.EndDate < now
ORDER BY L.ListedDate DESC;
ELSE
SELECT L.*
FROM Listing L
WHERE L.UserId = userId AND (L.ListedDate < now AND L.EndDate > now)
ORDER BY L.ListedDate DESC;
END IF;
END $$

CREATE PROCEDURE Listing_Insert(
IN listedDate DATETIME,
IN endDate DATETIME,
IN itemId INT(8),
IN userId INT(8),
IN resAmt DECIMAL(8,2),
IN shipAmt DECIMAL(8,2),
IN bidIncr DECIMAL(8,2),
OUT id INT(8)
)
BEGIN
INSERT INTO Listing
(ListedDate, EndDate, ItemId, ReserveAmount, BidIncrementAmount, ShippingAmount, UserId)
VALUES
(listedDate, endDate, itemId, resAmt, bidIncr, shipAmt, userId);

SELECT LAST_INSERT_ID() INTO id;
END$$

CREATE PROCEDURE Listing_Update(
IN id INT(8),
IN listedDate DATETIME,
IN endDate DATETIME,
IN itemId INT(8),
IN userId INT(8),
IN resAmt DECIMAL(8,2),
IN shipAmt DECIMAL(8,2),
IN bidIncr DECIMAL(8,2)
)
BEGIN
UPDATE 
Listing L
SET
L.ListedDate = listedDate, 
L.EndDate = endDate, 
L.ItemId = itemId, 
L.ReserveAmount = resAmt, 
L.BidIncrementAmount = bidIncr, 
L.ShippingAmount = shipAmt
WHERE
L.Id = id;


END$$

CREATE PROCEDURE Login_Get(
IN uName VARCHAR(50),
OUT retid INT(8),
OUT retpwd VARCHAR(200))
BEGIN

DECLARE userId INT(8);

SELECT 
U.Id INTO userId 
FROM 
UserDetail U 
WHERE 
U.Email = uName;

IF userId IS NOT NULL THEN
BEGIN
SELECT userId INTO retid;

SELECT U.Password INTO retpwd
FROM UserDetail U
WHERE U.Id = userId; 
END;
ELSE
BEGIN
SELECT NULL INTO retid;
SELECT NULL INTO retpwd;
END;
END IF;
END$$


CREATE PROCEDURE MediaItem_GetAllByItemId(
IN itmId int(8))
BEGIN
SELECT
M.*
FROM
MediaItem M
WHERE
M.ItemId = itmId;
END$$

CREATE PROCEDURE MediaItem_Insert(
IN itemId INT(8),
IN insertFileName VARCHAR(100),
IN prime INT(1),
IN stat INT(1),
OUT id INT(8)
)
BEGIN
DECLARE statusBit BIT;
DECLARE primaryBit BIT;

IF stat = 1 
THEN SET statusBit = 1; 
ELSE 
SET statusBit = 0;
END IF;

IF prime = 1 
THEN SET primaryBit = 1;
ELSE
SET primaryBit = 0;
END IF;

IF primaryBit = 1
THEN
-- Need to reset all other MediaItems (if any) to non primary
UPDATE MediaItem MI
SET MI.IsPrimary = 0
WHERE MI.ItemId = itemId;
END IF;

INSERT INTO MediaItem
(ItemId, `FileName`, IsPrimary, IsActive)
VALUES
(itemId, insertFileName, primaryBit, statusBit);

SELECT LAST_INSERT_ID() INTO id;
END$$

CREATE PROCEDURE MediaItem_UpdateStatus(
IN mIid int(8),
IN newStatus INT(1),
IN primaryStatus INT(1))
BEGIN

DECLARE statusBit BIT;
DECLARE primaryBit BIT;	

IF newStatus = 1 
THEN SET statusBit = 1; 
ELSE 
SET statusBit = 0;
END IF;

IF primaryStatus = 1 
THEN SET primaryBit = 1;
ELSE
SET primaryBit = 0;
END IF;


IF primaryBit = 1 THEN 
BEGIN
DECLARE itemId INT(8);
SELECT M.ItemId INTO itemId FROM MediaItem M WHERE M.Id = mIid LIMIT 1;

-- Need to reset all other MediaItems to non primary first
UPDATE MediaItem MI
SET MI.IsPrimary = 0
WHERE MI.ItemId = itemId;

-- Set MediaItem bound to this procedure as the primary
UPDATE MediaItem MI
SET MI.IsPrimary = 1
WHERE MI.Id = mIid;
END;
ELSE
UPDATE MediaItem MI
SET MI.IsPrimary = 0
WHERE MI.Id = mIid;
END IF;

-- Update Status of Media Item
UPDATE MediaItem MI
SET MI.IsActive = statusBit
WHERE MI.Id = mIid;

END$$

CREATE PROCEDURE OrderTrans_Insert(
IN sellerId INT(8),
IN purchasedId INT(8),
IN saleAmt DECIMAL(8,2),
IN listingId INT(8),
IN ccNo VARCHAR(20),
IN ccExp VARCHAR(5),
IN sAddresLine1 VARCHAR(60),
IN sAddresLine2 VARCHAR(60),
IN sAddressSuburb VARCHAR(30),
IN sAddressState VARCHAR(10),
IN sAddressZip VARCHAR(10),
IN shipFname VARCHAR(60),
IN shipLname VARCHAR(60),
OUT id INT(8),
OUT dt DATETIME
)
BEGIN
DECLARE insDT DATETIME;
SET insDT = NOW();

INSERT INTO OrderTransaction
(SellingUserDetailId, PurchasingUserId, TransactionDate, CreditCardNo, CreditCardExp,
SaleAmount, ShippingFirstName, ShippingLastName, ShippingAddressLine1, ShippingAddressLine2, 
ShippingAddressSuburb, ShippingAddressState, ShippingAddressZip)
VALUES
(sellerId, purchasedId, insDT, ccNo, ccExp, saleAmt, shipFname, shipLname,
sAddresLine1, sAddresLine2, sAddressSuburb, sAddressState, sAddressZip);

SELECT LAST_INSERT_ID() INTO id;
SELECT insDT INTO dt;

END$$

CREATE PROCEDURE OrderTrans_LoadCollection(
IN userId INT(8),
IN sprocAction VARCHAR(20)
)
BEGIN
IF sprocAction = 'buyer' THEN -- get buyer transactions
SELECT OT.*
FROM OrderTransaction OT
WHERE OT.PurchasingUserId = userId;
ELSE -- get seller transactions
SELECT OT.*
FROM OrderTransaction OT
WHERE OT.SellingUserDetailId = userId;
END IF;
END $$

CREATE PROCEDURE Search_GetResults(
IN queryTerm varchar(100))
BEGIN

DECLARE NowDt DATETIME;
SET NowDt = NOW();

SELECT 
L.Id AS ListingId,
I.`Name` AS ItemName,
C.CategoryName,
L.EndDate,
M.`FileName` AS MediaFileName
FROM 
Item I 
LEFT JOIN Listing L ON I.Id = L.ItemId
LEFT JOIN MediaItem M ON I.Id = M.ItemId AND M.IsPrimary = 1 AND M.IsActive = 1 
LEFT JOIN Category C ON I.CategoryId = C.Id
WHERE
I.`Name` LIKE CONCAT('%', queryTerm, '%')
AND L.EndDate > NowDt
ORDER BY
I.`Name`;
END$$ 

CREATE PROCEDURE User_Insert(
IN firstName VARCHAR(60),
IN lastName VARCHAR(60),
IN email VARCHAR(50),
IN userName VARCHAR(50),
IN userPwd VARCHAR(200),
IN bAddresLine1 VARCHAR(60),
IN bAddresLine2 VARCHAR(60),
IN bAddressSuburb VARCHAR(30),
IN bAddressState VARCHAR(10),
IN bAddressZip VARCHAR(10),
OUT id INT(8),
OUT createdDate DATETIME
)
BEGIN
DECLARE insDT DATETIME;
SET insDT = NOW();

INSERT INTO UserDetail
(DateCreated, FirstName, LastName, Email, DisplayUserName,
`Password`, BillingAddressLine1, BillingAddressLine2, BillingAddressSuburb,
BillingAddressState, BillingAddressZip)
VALUES
(insDT, firstName, lastName, email, userName, userPwd,
bAddresLine1, bAddresLine2, bAddressSuburb, bAddressState, bAddressZip);

SELECT LAST_INSERT_ID() INTO id;
SELECT insDT INTO createdDate;


END$$

CREATE PROCEDURE User_Load(
IN userId int(4))
BEGIN
SELECT
U.Id,
U.DateCreated,
U.FirstName,
U.LastName,
U.Email,
U.DisplayUserName,
U.BillingAddressLine1,
U.BillingAddressLine2,
U.BillingAddressSuburb,
U.BillingAddressState,
U.BillingAddressZip
FROM
UserDetail U
WHERE
U.Id = userId;
END$$

CREATE PROCEDURE User_Update(
IN id INT(8),
IN firstName VARCHAR(60),
IN lastName VARCHAR(60),
IN email VARCHAR(50),
IN userName VARCHAR(50),
IN bAddresLine1 VARCHAR(60),
IN bAddresLine2 VARCHAR(60),
IN bAddressSuburb VARCHAR(30),
IN bAddressState VARCHAR(10),
IN bAddressZip VARCHAR(10)
)
BEGIN

UPDATE 
UserDetail U
SET
U.FirstName = firstName,
U.LastName = lastName,
U.Email = email,
U.DisplayUserName = userName,
U.BillingAddressLine1 = bAddresLine1,
U.BillingAddressLine2 = bAddresLine2,
U.BillingAddressSuburb = bAddressSuburb,
U.BillingAddressState = bAddressState,
U.BillingAddressZip = bAddressZip
WHERE
U.Id = id;

END$$

CREATE PROCEDURE User_UpdatePwd(
IN uId INT(8),
IN userPwd VARCHAR(200)
)
BEGIN

UPDATE UserDetail
SET `Password` = userPwd
WHERE
Id = uId;
END $$

CREATE PROCEDURE OrderTrans_Create()
BEGIN
DECLARE lastRunTime DATETIME;
DECLARE nowDT DATETIME;
SET nowDT = NOW();

SELECT TransactionDate 
FROM OrderTransaction 
ORDER BY TransactionDate DESC
LIMIT 1
INTO lastRunTime;

CREATE TEMPORARY TABLE Temp(
sellerId INT(8),
purchasedId INT(8),
saleAmt DECIMAL(8,2),
listingId INT(8),
ccNo VARCHAR(20),
ccExp VARCHAR(5),
sAddresLine1 VARCHAR(60),
sAddresLine2 VARCHAR(60),
sAddressSuburb VARCHAR(30),
sAddressState VARCHAR(10),
sAddressZip VARCHAR(10),
shipFname VARCHAR(60),
shipLname VARCHAR(60)
)engine=memory;

CREATE TEMPORARY TABLE TempListings( listingId INT(8) );

-- Getting all the listings that have ended	since last run
INSERT INTO TempListings (listingId)
SELECT L.Id
FROM Listing L
WHERE L.EndDate > lastRunTime AND L.EndDate < nowDT
AND L.Id IN (
SELECT B.ListingId
FROM Bid B
WHERE B.`Status` = 0
);

-- Get remaining data for ordertransaction and insert into Temp
INSERT INTO 
Temp (sellerId, purchasedId, ccNo, ccExp, saleAmt, listingId, shipFname, shipLname,
sAddresLine1, sAddresLine2, sAddressSuburb, sAddressState, sAddressZip)
SELECT
L.UserId,
B.BiddingUserId,
'Not Set',
'Not Set',
(B.BidValue + IFNULL(L.ShippingAmount, 0)),
TL.listingId,
BU.FirstName,
BU.LastName,
BU.BillingAddressLine1,
BU.BillingAddressLine2,
BU.BillingAddressSuburb,
BU.BillingAddressState,
BU.BillingAddressZip
FROM 
TempListings TL 
LEFT JOIN Listing L ON TL.listingId = L.Id
LEFT JOIN Bid B ON TL.listingId = B.ListingId AND B.Id = (SELECT Id FROM Bid WHERE ListingId = TL.listingId ORDER BY BidValue DESC LIMIT 1)
LEFT JOIN UserDetail BU ON B.BiddingUserId = BU.Id;


-- Insert new ordertrans into table	
INSERT INTO 
OrderTransaction (SellingUserDetailId, PurchasingUserId, TransactionDate, CreditCardNo, CreditCardExp,
SaleAmount, ShippingFirstName, ShippingLastName, ShippingAddressLine1, ShippingAddressLine2, 
ShippingAddressSuburb, ShippingAddressState, ShippingAddressZip, ListingId)
SELECT
T.sellerId,
T.purchasedId,
nowDT,
T.ccNo,
T.ccExp,
T.saleAmt,
T.shipFname,
T.shipLname,
T.sAddresLine1,
T.sAddresLine2,
T.sAddressSuburb,
T.sAddressState,
T.sAddressZip, 
T.listingId
FROM
Temp T;

DROP TABLE IF EXISTS Temp;
DROP TABLE IF EXISTS TempListings;

END$$

DELIMITER ;
