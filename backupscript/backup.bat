
set a=%DATE%
set b=%TIME%
SET DD=%date:~7,2%
SET MM=%date:~4,2%
SET YYYY=%date:~10,4%
SET HH=%TIME:~0,2%
SET MI=%TIME:~3,2%
SET SS=%TIME:~6,2%
SET T=%TIME: =0%

SET BASE_NAME_ORCL=%DD%_%MM%_%YYYY%_%HH%_%MI%_%SS%_orcl_fullexp
SET DUMPFILE_NAME=%BASE_NAME%.dmp

REM Script begin
REM expdp, full backup to "dpdir", flashback_time=systimestamp for consistency
exp system/swapp123@orclkadwa full=y file=e:\databackup\%BASE_NAME_ORCL%.dmp



