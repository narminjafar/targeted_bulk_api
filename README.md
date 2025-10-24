Kampaniya Göndərişi & Performans Strategiyası (Indexing & Architecture)

Bu layihə, xüsusilə böyük həcmli e-poçt kampaniyaları ilə işləyərkən yüksək performans, etibarlılıq və təmiz arxitektura tələblərinə cavab vermək üçün qurulmuşdur.

**1. İlkin Məlumatların Hazırlanması (Seeding)**

Layihəni işə saldıqdan sonra verilənlər bazasını hazırlayın və test məlumatlarını yaradın.

Migrasiyaları icra edin:

php artisan migrate

Seed məlumatlarını əlavə edin:

php artisan db:seed

Qeyd: User və Segment nümunə məlumatları bu addımda yaradılacaq.

Daha sonra php artisan serve  ve **/api/documentation **

**2. Autentifikasiya (Authentication)**

API-nin əksər endpointləri Bearer Token ilə qorunur. Giriş üçün /login endpoint-indən istifadə edin.   

Autentifikasiya Metodu

Başlıq (Header) Formatı

Bearer Token

Authorization: Bearer <token_dəyəri>

3. Nümunə API Çağırışları (cURL Examples)

Əsas funksionallıq üçün nümunə çağırışlar (fərz edilir ki, uğurlu giriş nəticəsində TOKEN əldə olunub).

3.1. Giriş (Token Əldə Etmə)

Sizin API-nizin /login endpoint-inə POST sorğusu
curl -X POST "http://localhost:8000/api/v1/login"
-H "Content-Type: application/json"
-d '{ "email": "testuser@example.com", "password": "password" }'

Nəticədə Token əldə ediləcək.
** 4. Təmiz Arxitektura Təbəqələşməsi**

Layihə Təmiz Lay (Clean Layering) prinsiplərinə əsaslanır:

Controller/Konsol: İlkin sorğunu alır, Service təbəqəsini çağırır.

Service (SegmentService): Biznes məntiqini ehtiva edir (məsələn, istifadəçilərin seqmentlərə görə filtrlənməsi).

Repository (CampaignRepository, UserRepository): Verilənlərin çəkilməsi və saxlanması məntiqini Model (Eloquent) siniflərindən ayırır.

Model: Yalnız DB strukturlarını təmsil edir. ** 5. Mail Bütünlüyü və Performans Təkmilləşdirmələri**

Bhunk və N+1 Həlli

N+1 Qaçınma: SendCampaignEmailsJob daxilindəki istifadəçi filtrləməsi (abonelikdən çıxma və əvvəlcədən göndərilmə yoxlamaları) whereIn sorğuları vasitəsilə həyata keçirilir. Bu, hər bir istifadəçi üçün fərdi DB sorğusu atmaq (N+1) əvəzinə, hər bir 2000 istifadəçi bloku üçün yalnız iki sorğuya endirilmişdir. Bu, performans üçün KRİTİK FAKTORDUR.

Chunking: chunkById(2000, ...) istifadə edilməsi əsas Job-un DB əlaqəsinin vaxt aşımına uğramasının qarşısını alır və böyük cədvəllərdə OFFSET istifadəsindən daha sürətli işləyir.

Mail Boru Xətti Konfiqurasiyası Nümunədə class sabitləri kimi verilib:

6. İndeksləmə Strategiyası (DB Səmərəliliyi)

Aşağıdakı cədvəllərdə müvafiq indekslərin təmin edilməsi, Job-ların icra müddətini və ümumi verilənlər bazası yükünü minimuma endirmək üçün əsas şərtdir.

Cədvəl

Təklif Edilən İndekslər

Əsaslandırma

users

id (Primary Key)

chunkById funksiyası üçün mütləqdir.

user_unsubscribed

user_id (Birlikdə)

SendCampaignEmailsJob daxilindəki whereIn('user_id', ...) sorğusunun sürətli icrası üçün.

campaign_user_sent

campaign_id, user_id

TƏRKİBİ İNDEKSLƏMƏ (Composite Index): (campaign_id, user_id) birləşməsi üzrə unikal indeks olmalıdır. Bu, həm exists() yoxlamasını, həm də DB::count() sorğusunu yüksək sürətlə yerinə yetirməyi təmin edir.

campaigns

id (Primary Key)

CampaignRepository tərəfindən find əməliyyatlarının sürətli olması üçün.

{"email_verified":true,"marketing_opt_in":true,"purchased":{"category":"electronics"}}

**public/docs/swagger.yaml**
