DELIMITER $$

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