import dayjs from 'dayjs'

type DataType = {
  日付: Date
  小計: number
}

type GraphDataType = {
  label: string
  transition: number
  cumulative: number
}

export default (data: DataType[]) => {
  const graphData: GraphDataType[] = []
  const now = dayjs()
  const twoMonthAgo = now.subtract(2, 'month')
  let patSum = 0
  data
    .filter((d) => dayjs(d['日付']) < now)
    .forEach((d) => {
      const subTotal = d['小計']
      if (!isNaN(subTotal)) {
        patSum += subTotal
        graphData.push({
          label: dayjs(d['日付']).format('YYYY/MM/DD'),
          transition: subTotal,
          cumulative: patSum,
        })
      }
    })
  return graphData.filter((d) => dayjs(d.label) >= twoMonthAgo)
}
