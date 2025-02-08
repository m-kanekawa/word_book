<?php

class Config
{
  const IS_LOGFILE      = true;
  const LOG_LEVEL       = 3;          // 0=ERROR/1=WARN/2=INFO/3=DEBUG
  const LOGDIR_PATH     = '/www/logs/';
  const LOGFILE_NAME    = 'dict';
  const LOGFILE_MAXSIZE = 10485760;   // Byte
  const LOGFILE_PERIOD  = 30;         // days
}
