<?hh // strict

namespace kilahm\chores\enum;

enum SqlType : string as string
{
    Tint = 'INTEGER';
    Tstring = 'VARCHAR';
    Tbool = 'TINYINT';
    Tflaot = 'DOUBLE';
    Tnum = 'NUMERIC';
    Tblob = 'BLOB';
}
