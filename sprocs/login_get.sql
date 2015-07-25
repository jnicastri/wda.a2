DELIMITER $$

CREATE PROCEDURE Login_Get(
	IN uName VARCHAR(50),
	OUT retid INT(8),
	OUT retpwd VARCHAR(200))
BEGIN
	
	DECLARE userId INT(8);
	
	SELECT 
		U.Id INTO userId 
	FROM 
		UserDetail U 
	WHERE 
		U.Email = uName;
	
	IF userId IS NOT NULL THEN
		BEGIN
			SELECT userId INTO retid;
			
			SELECT U.Password INTO retpwd
			FROM UserDetail U
			WHERE U.Id = userId; 
		END;
	ELSE
		BEGIN
			SELECT NULL INTO retid;
			SELECT NULL INTO retpwd;
		END;
	END IF;
END$$