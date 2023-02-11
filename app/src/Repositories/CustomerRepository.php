<?php

namespace Repositories;

class CustomerRepository extends AbstractRepository
{
    
    /**
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return int
     */
    public function countBetweenDates(\DateTimeImmutable $start, \DateTimeImmutable $end): int
    {
        $stmt = $this->db->prepare(
            "
select count(distinct (c.id))
from customers c
	     join orders o on c.id = o.customer_id
WHERE date(o.purchase_date) >= date(FROM_UNIXTIME(?/1000))
  and date(o.purchase_date) <= date(FROM_UNIXTIME(?/1000));"
        );
        $stmt->execute([$start->getTimestamp(), $end->getTimestamp()]);
        return (int)$stmt->fetchColumn();
    }
}