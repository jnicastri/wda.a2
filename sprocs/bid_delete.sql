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