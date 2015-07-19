DELIMTER $$

CREATE PROCEDURE Item_Load(
	IN userId int(4))
BEGIN
	SELECT
		I.Id,
		I.DateCreated,
		I.`Name`,
		I.LongDescription,
		C.Id AS CatId,
		C.CategoryName AS CatName,
		C.CategoryDescription AS CatDesc
	FROM
		Item I JOIN Category C ON I.CategoryId = C.Id
	WHERE
		I.Id = userId;
END$$