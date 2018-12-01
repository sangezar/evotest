#!/usr/bin/env bash

file=$1
threshold=$2

if ! [[ $2 ]];
then
  echo "[Error] Пропущенны аргументы."
  exit 25
fi

if ! [[ -f $1 ]]; then
    echo "[Error] Нет такого файла."
    exit 25
fi

while read line ; do
  IFS=","
  set -- $line

  uid=$1
  date=$2
  sum=$3

  if ((  $(echo "$sum >= $threshold" |bc -l) ))
  then
    echo -e "$uid $date $sum"
  fi
done < $file
