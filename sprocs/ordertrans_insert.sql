DELIMITER $$

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
		(sellerId, purchasedId, insDT, ccNo, ccExp, saleAmt, shipFname. shipLname,
		sAddresLine1, sAddresLine2, sAddressSuburb, sAddressState, sAddressZip);
		
	SELECT LAST_INSERT_ID() INTO id;
	SELECT insDT INTO dt;
	
END$$