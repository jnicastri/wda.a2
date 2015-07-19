DELIMITER $$

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