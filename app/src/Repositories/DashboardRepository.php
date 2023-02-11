<?php

namespace Repositories;

class DashboardRepository extends AbstractRepository
{
    /**
     *
     *
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return array
     */
    public function getDashboardDataBetweenDates(\DateTimeImmutable $start, \DateTimeImmutable $end): array
    {
        $stmt = $this->db->prepare(
            <<<SQL
            with calendar as (select *
                  from (select adddate('1970-01-01',
                                       t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) calendar_date
                        from (select 0 i
                              union
                              select 1
                              union
                              select 2
                              union
                              select 3
                              union
                              select 4
                              union
                              select 5
                              union
                              select 6
                              union
                              select 7
                              union
                              select 8
                              union
                              select 9) t0,
                             (select 0 i
                              union
                              select 1
                              union
                              select 2
                              union
                              select 3
                              union
                              select 4
                              union
                              select 5
                              union
                              select 6
                              union
                              select 7
                              union
                              select 8
                              union
                              select 9) t1,
                             (select 0 i
                              union
                              select 1
                              union
                              select 2
                              union
                              select 3
                              union
                              select 4
                              union
                              select 5
                              union
                              select 6
                              union
                              select 7
                              union
                              select 8
                              union
                              select 9) t2,
                             (select 0 i
                              union
                              select 1
                              union
                              select 2
                              union
                              select 3
                              union
                              select 4
                              union
                              select 5
                              union
                              select 6
                              union
                              select 7
                              union
                              select 8
                              union
                              select 9) t3,
                             (select 0 i
                              union
                              select 1
                              union
                              select 2
                              union
                              select 3
                              union
                              select 4
                              union
                              select 5
                              union
                              select 6
                              union
                              select 7
                              union
                              select 8
                              union
                              select 9) t4) v
                  where calendar_date between date(FROM_UNIXTIME(? / 1000)) and date(FROM_UNIXTIME(? / 1000))),
     purchase_data as (select date(o.purchase_date)       as purchase_date,
                              count(distinct (c.id))      as customers_tally,
                              count(distinct (o.id))      as orders_tally
                       from orders o
	                            join customers c on c.id = o.customer_id
                       WHERE date(o.purchase_date) >= date(FROM_UNIXTIME(? / 1000))
	                     and date(o.purchase_date) <= date(FROM_UNIXTIME(? / 1000))
                       group by date(o.purchase_date)
                       order by date(o.purchase_date))

select cal.calendar_date,
       coalesce(pd.orders_tally, 0)    as orders_tally,
       coalesce(pd.customers_tally, 0) as customers_tally
from calendar cal
	     left join purchase_data pd
	               on pd.purchase_date = cal.calendar_date
order by cal.calendar_date;

SQL
        
        );
        $stmt->execute([$start->getTimestamp(), $end->getTimestamp(), $start->getTimestamp(), $end->getTimestamp()]);
        return (array)$stmt->fetchAll(\PDO::FETCH_UNIQUE);
    }
}