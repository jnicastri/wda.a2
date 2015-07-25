DELIMITER $$

CREATE PROCEDURE OrderTrans_LoadCollection(
	IN userId INT(8),
	IN sprocAction VARCHAR(20)
)
BEGIN
	IF sprocAction = 'buyer' THEN -- get buyer transactions
		SELECT OT.*
		FROM OrderTransaction OT
		WHERE OT.PurchasingUserId = userId;
	ELSE -- get seller transactions
		SELECT OT.*
		FROM OrderTransaction OT
		WHERE OT.SellingUserDetailId = userId;
	END IF;
END $$