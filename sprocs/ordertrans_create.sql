DELIMITER $$

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
		'None',
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