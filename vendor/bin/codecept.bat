@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../codeception/base/codecept
php "%BIN_TARGET%" %*
