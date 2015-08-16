DELIMITER $$

CREATE PROCEDURE User_UpdatePwd(
	IN uId INT(8),
	IN userPwd VARCHAR(200)
)
BEGIN
	
	UPDATE UserDetail
		SET `Password` = userPwd
	WHERE
		Id = uId;
END $$