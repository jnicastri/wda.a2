DELIMITER $$

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
END$$