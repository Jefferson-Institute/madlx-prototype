import pandas as pd


with open("../storage/app/public/data.csv", "r") as f:
    lines = f.readlines()
datac = {}
totallenght = {}
for line in lines:
  line = line.rstrip("\n")
  content_list = line.split(";")
  if content_list[0] not in datac:
    datac[content_list[0]] = []
  else:
    datac[content_list[0]].append(float(content_list[1]))

  totallenght[content_list[0]] = len(datac[content_list[0]])
  #datac[content_list[0]] = [datac[content_list[0]], float(content_list[1])]

max = 0
columns = []
for value in totallenght:
  columns.append(value)
  if(totallenght[value] > max):
    max = totallenght[value]

for value in datac:
  while len(datac[value]) < max:
        datac[value].append(0)


dd = pd.DataFrame(datac, columns = columns)
corrMatrix = dd.corr()
corrMatrix.to_csv("../storage/app/public/assets/data2.csv")