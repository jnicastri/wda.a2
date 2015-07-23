DELIMITER $$

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