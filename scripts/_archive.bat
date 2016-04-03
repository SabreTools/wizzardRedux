@echo off

REM Set the title of the window
TITLE Archive Files and Folders

REM Set unicode compatibility for odd names
chcp 65001

REM Add 7-zip to the path to make it easier to call
PATH=%PATH%;"C:\Program Files\7-Zip\";"C:\Program Files (x86)\7-Zip\"

REM Set output archive type, with period
set ext=.7z
REM Set if current directory is parsed (true)
set cur=true
REM Set if directories are archived (true)
set sub=false
REM Set if cleanup is done or not (true)
set cleanup=true

REM Set all possible output formats
set outlist=.7z .bz2 .gz .tar .wim .xz .zip

REM Check the extension against known available output formats
for %%O in (%outlist%) do (
	if "%ext%"=="%%O" goto ArchiveFiles
)

echo The entered output file type is not valid, use one of the following instead:
echo .7z, .xz, .bz2, .gz, .tar, .zip, .wim
pause
goto EOF

:ArchiveFiles
REM Take care of current folder, if applicable
if "%cur%"=="true" (
	echo Processing current folder
	for /F "delims=" %%A in ('dir /A-D /B') do (
		if not "%%~xA"=="%ext%" if not "%%A"=="%~n0%~x0" if not "%%A"==".." if not "%%A"=="." (
			7z.exe a "%%~nA%ext%" "%%A"
			if "%cleanup%"=="true" (
				echo Deleting %%A
				del "%%A"
			)
		)
	)
)

:ArchiveSubfolder
REM Take care of subfolders, if applicable
if "%sub%"=="true" (
	echo Processing subfolders
	for /F "delims=" %%D in ('dir /AD /B') do (
		if not "%%D"==".." if not "%%D"=="." (
			echo Moving into %%D
			cd "%%D"
			7z.exe a "..\%%D%ext%"
			echo Going back to root folder
			cd ..
			if "%cleanup%"=="true" (
				echo Deleting %%D
				rmdir /S /Q "%%D"
			)
		)
	)
)