CREATE PROCEDURE Bid_LoadBidsByListing(
	IN listingId INT(8)
)
BEGIN
	SELECT B.*
	FROM Bid B
	WHERE B.ListingId = listingId AND B.`Status` = 0;	
	
END$$