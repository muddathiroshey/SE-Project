<?php
// namespace App\Models;

// class Bid extends Data 
// {
//     // المتغيرات التي ستحمل البيانات
//     public $proposal_message;
//     public $key_differentiators;
//     public $relevant_work;
//     public $total_bid_amount;
//     public $bid_rationale;
//     public $availability_slots;
//     public $start_date;

    

//     public function save(Bid $bidObject) 
// {
// public $proposal_message;
//     public $key_differentiators;
//     public $relevant_work;
//     public $total_bid_amount;
//     public $bid_rationale;
//     public $availability_slots;
//     public $start_date;

//     public function save(Bid $bidObject) 
//     {
//         // 1. فتح الاتصال باستخدام دالة getDb الموجودة في كلاس Data
//         $conn = $this->getDb(); 

//         // 2. استخدام علامات الاستفهام (?) بدلاً من الألفاظ (مثل :proposal)
//         $sql = "INSERT INTO bids (
//                     proposal_message, 
//                     key_differentiators, 
//                     relevant_work, 
//                     total_bid_amount, 
//                     bid_rationale, 
//                     start_date,
//                     availability_slots 
//                 ) VALUES (?, ?, ?, ?, ?, ?, ?)";

//         $stmt = $conn->prepare($sql);

//         // 3. ربط المتغيرات (s = string, d = double/decimal)
//         // الترتيب: 5 نصوص، ثم رقم عشري واحد، ثم نص واحد (للتاريخ)
//         $stmt->bind_param("sssdsss", 
//             $bidObject->proposal_message,
//             $bidObject->key_differentiators,
//             $bidObject->relevant_work,
//             $bidObject->total_bid_amount,
//             $bidObject->bid_rationale,
//             $bidObject->start_date
//             $bidObject->availability_slots,
//         );

//         // $stmt->execute();

//         if (!$stmt->execute()) {
//     // هذا السطر سيوقف الكود ويخبرك بالضبط لماذا فشل SQL
//     die("SQL Error: " . $stmt->error . " | Error Number: " . $conn->errno);
// }
        
//         // 4. الحصول على ID العرض الذي تم إدخاله
//         $newId = $conn->insert_id;

//         $stmt->close();
//         // ملاحظة: لا نغلق $conn هنا إذا كنا سنستخدمه فوراً للمراحل، 
//         // لكن بما أن الكنترولر ينشئ كائناً جديداً للمراحل، فلا بأس.
        
//         return $newId;
//     }
// }
// }


namespace App\Models;

class Bid extends Data 
{
    public $job_id;
    public $user_id;
    public $proposal_message;
    public $key_differentiators;
    public $relevant_work;
    public $total_bid_amount;
    public $bid_rationale;
    public $start_date;
    public $free_reviews;
    public $review_price;
     public $availability_slots;

// و
    public function save(Bid $bidObject) 
    {
        $conn = $this->getDb(); 

        $sql = "INSERT INTO bids (
                    job_id,
                    user_id,
                    proposal_message, 
                    key_differentiators, 
                    relevant_work, 
                    total_bid_amount, 
                    bid_rationale, 
                    start_date,
                    free_reviews,
                    review_price,
                    availability_slots 
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // تم إصلاح الفاصلة المفقودة هنا والترتيب
        $stmt->bind_param("iisssdssiis", 
            $bidObject->job_id,
            $bidObject->user_id,
            $bidObject->proposal_message,
            $bidObject->key_differentiators,
            $bidObject->relevant_work,
            $bidObject->total_bid_amount,
            $bidObject->bid_rationale,
            $bidObject->start_date, // تم إضافة فاصلة هنا
            $bidObject->free_reviews,
            $bidObject->review_price,
            $bidObject->availability_slots
        );

        if (!$stmt->execute()) {
            die("SQL Error: " . $stmt->error . " | Error Number: " . $conn->errno);
        }
        
        $newId = $conn->insert_id;
        $stmt->close();
        
        return $newId;
    }
}
?>