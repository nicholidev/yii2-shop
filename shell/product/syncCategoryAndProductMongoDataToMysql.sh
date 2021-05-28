#!/bin/sh

# 1.if you want sync mongodb product and category data to mysql storage, you can run this script
# 2.this script do not delete origin category and product data, It only adds data on the original basis
# 3.sync product and category data, url rewrite table data Will be overwritten
#

Cur_Dir=$(cd `dirname $0`; pwd)

# get category all count.
count=`$Cur_Dir/../../../../../yii category/mongodatatomysql/synccount`
pagenum=`$Cur_Dir/../../../../../yii category/mongodatatomysql/syncpagenum`

echo "There are $count categorys to process"
echo "There are $pagenum pages to process"
echo "##############ALL BEGINING###############";
for i in `seq $pagenum`
do
   $Cur_Dir/../../../../../yii category/mongodatatomysql/sync $i
   echo "Page $i done"
done

# set category parent_id by  origin_mongo_id and origin_mongo_parent_id
echo "There are $count categorys to process"
echo "There are $pagenum pages to process"
echo "##############ALL BEGINING###############";
for i in `seq $pagenum`
do
   $Cur_Dir/../../../../../yii category/mongodatatomysql/initparentid $i
   echo "Page $i done"
done


# get product all count.
count=`$Cur_Dir/../../../../../yii product/mongodatatomysql/synccount`
pagenum=`$Cur_Dir/../../../../../yii product/mongodatatomysql/syncpagenum`

echo "There are $count products to process"
echo "There are $pagenum pages to process"
echo "##############ALL BEGINING###############";
for i in `seq $pagenum`
do
   $Cur_Dir/../../../../../yii product/mongodatatomysql/sync $i
   echo "Page $i done"
done

###### 1.Sync Section End

echo "##############ALL COMPLETE###############";

