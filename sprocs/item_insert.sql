DELIMITER $$

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