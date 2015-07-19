DELIMITER $$

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