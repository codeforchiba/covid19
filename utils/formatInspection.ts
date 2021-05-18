import dayjs from 'dayjs'

type DataType = {
  判明日: Date
  市内: number
  その他: number
}

type GraphDataType = {
  label: string
  city: number
  cumCity: number
  other: number
  cumOther: number
}

const formatInspectionLabel = (data: DataType[]) => {
  const now = dayjs()
  const twoMonthAgo = now.subtract(2, 'month')

  return data
    .map((d) => dayjs(d['判明日']))
    .filter((d) => d >= twoMonthAgo)
    .map((d) => d.format('YYYY/MM/DD'))
}

const formatInspectionGraph = (data: DataType[]) => {
  const now = dayjs()
  const twoMonthAgo = now.subtract(2, 'month')
  let sumCity = 0
  let sumOther = 0
  const graphData: GraphDataType[] = []

  data.forEach((d) => {
    const valueCity = d['市内']
    const valueOther = d['その他']

    sumCity += valueCity
    sumOther += valueOther

    graphData.push({
      label: dayjs(d['判明日']).format('YYYY/MM/DD'),
      city: valueCity,
      other: valueOther,
      cumCity: sumCity,
      cumOther: sumOther,
    })
  })
  return graphData.filter((d) => dayjs(d.label) >= twoMonthAgo)
}

export { formatInspectionLabel, formatInspectionGraph }
