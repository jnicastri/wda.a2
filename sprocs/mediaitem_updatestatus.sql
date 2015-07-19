DELIMTER $$

CREATE PROCEDURE MediaItem_UpdateStatus(
	IN mIid int(8),
	IN newStatus INT(1),
	IN primaryStatus INT(1))
BEGIN

	DECLARE statusBit BIT;
	DECLARE primaryBit BIT;	
	
	IF newStatus = 1 
		THEN SET statusBit = 1; 
	ELSE 
		SET statusBit = 0;
	END IF;
	
	IF primaryStatus = 1 
		THEN SET primaryBit = 1;
	ELSE
		SET primaryBit = 0;
	END IF;
	
	
	IF primaryBit = 1 THEN 
		BEGIN
			DECLARE itemId INT(8);
			SELECT M.ItemId INTO itemId FROM MediaItem M WHERE M.Id = mIid LIMIT 1;
			
			-- Need to reset all other MediaItems to non primary first
			UPDATE MediaItem MI
			SET MI.IsPrimary = 0
			WHERE MI.ItemId = itemId;
			
			-- Set MediaItem bound to this procedure as the primary
			UPDATE MediaItem MI
			SET MI.IsPrimary = 1
			WHERE MI.Id = mIid;
		END;
	ELSE
		UPDATE MediaItem MI
		SET MI.IsPrimary = 0
		WHERE MI.Id = mIid;
	END IF;
	
	-- Update Status of Media Item
	UPDATE MediaItem MI
	SET MI.IsActive = statusBit
	WHERE MI.Id = mIid;
	
END$$