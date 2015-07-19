DELIMITER $$

CREATE PROCEDURE MediaItem_Insert(
	IN itemId INT(8),
	IN insertFileName VARCHAR(100),
	IN prime INT(1),
	IN stat INT(1),
	OUT id INT(8)
)
BEGIN
	DECLARE statusBit BIT;
	DECLARE primaryBit BIT;
	
	IF stat = 1 
		THEN SET statusBit = 1; 
	ELSE 
		SET statusBit = 0;
	END IF;
	
	IF prime = 1 
		THEN SET primaryBit = 1;
	ELSE
		SET primaryBit = 0;
	END IF;
	
	IF primaryBit = 1
		THEN
			-- Need to reset all other MediaItems (if any) to non primary
			UPDATE MediaItem MI
			SET MI.IsPrimary = 0
			WHERE MI.ItemId = itemId;
	END IF;
	
	INSERT INTO MediaItem
		(ItemId, `FileName`, IsPrimary, IsActive)
	VALUES
		(itemId, insertFileName, primaryBit, statusBit);
		
	SELECT LAST_INSERT_ID() INTO id;
END$$