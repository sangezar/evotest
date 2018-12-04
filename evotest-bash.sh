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

echo 'Считывание файла'
readarray resultArray < $1

echo 'Сортировка'
IFS=$'\n' sorted=($(sort <<<"${resultArray[*]:1}"))

echo 'Собираем массив для вывода' $'\n'
uid=0
date=0
sum=0
arr=()

for value in "${sorted[@]}"
do
  IFS=","
    set -- $value

    if (( $uid != $1 ))
    then
      uid=$1
      date=$2
      sum=$3
    fi

    if (( $(echo "$sum >= $threshold" | bc -l) ))
    then
      subArr=($uid $date)
      arr[$uid]=${subArr[@]}
      continue
    fi

    if (( $uid == $1 ))
    then
      date=$2
      sum=$(echo "$sum+$3" | bc -l )
    fi
done

printf "%s\n" "${arr[@]}"
