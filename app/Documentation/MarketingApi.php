<?php

namespace App\Documentation;

/**
 * @OA\Info(
 *     title="Marketing APIs",
 *     version="1.0.0",
 *     description="Marketing kampaniyaları və segment idarəetmə API-si",
 *     @OA\Contact(email="support@example.com")
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

class MarketingApi
{
    /**
     * @OA\Post(
     *     path="/register",
     *     operationId="register",
     *     tags={"Auth"},
     *     summary="Yeni istifadəçi qeydiyyatı",
     *     description="İstifadəçi adı, email və şifrə ilə qeydiyyatdan keçir və JWT token qaytarır.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Aylin M.", description="İstifadəçinin tam adı"),
     *             @OA\Property(property="email", type="string", format="email", example="aylin1@example.com", description="Unikal email ünvanı"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123", minLength=6, description="Şifrə (minimum 6 simvol)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Uğurlu qeydiyyat",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Aylin M."),
     *                 @OA\Property(property="email", type="string", example="aylin1@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasiya xətası",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email has already been taken."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email has already been taken.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     *     path="/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="İstifadəçi girişi",
     *     description="Email və şifrə ilə sistemə giriş edib JWT token əldə edin.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="aylin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Uğurlu giriş",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Yanlış email və ya şifrə",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login() {}

    /**
     * @OA\Get(
     *     path="/segments",
     *     operationId="getSegments",
     *     tags={"Segments"},
     *     summary="Bütün seqmentlərin siyahısı",
     *     description="Pagination ilə seqmentlərin siyahısını gətirir.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Hər səhifədə göstəriləcək element sayı",
     *         required=false,
     *         @OA\Schema(type="integer", default=15, minimum=1, maximum=100, example=4)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Səhifə nömrəsi",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, minimum=1, example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Uğurlu cavab",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=7),
     *                     @OA\Property(property="name", type="string", example="Example Segment"),
     *                     @OA\Property(property="filter_json", type="object"),
     *                     @OA\Property(property="member_count", type="integer", example=150),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=2),
     *                 @OA\Property(property="per_page", type="integer", example=4),
     *                 @OA\Property(property="total", type="integer", example=50),
     *                 @OA\Property(property="last_page", type="integer", example=13)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Autentifikasiya tələb olunur",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Unauthenticated."))
     *     )
     * )
     */
    public function getSegments() {}

    /**
     * @OA\Post(
     *     path="/segments",
     *     operationId="createSegment",
     *     tags={"Segments"},
     *     summary="Yeni seqment yarat",
     *     description="Filtr şərtləri ilə yeni müştəri seqmenti yaradır.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","filter_json"},
     *             @OA\Property(property="name", type="string", example="Example Segment"),
     *             @OA\Property(
     *                 property="filter_json",
     *                 type="object",
     *                 @OA\Property(property="email_verified", type="boolean", example=true),
     *                 @OA\Property(property="marketing_opt_in", type="boolean", example=true),
     *                 @OA\Property(
     *                     property="registered_between",
     *                     type="array",
     *                     @OA\Items(type="string", format="date"),
     *                     example={"2025-01-01", "2025-10-01"}
     *                 ),
     *                 @OA\Property(property="last_active_days", type="integer", example=30),
     *                 @OA\Property(
     *                     property="purchased",
     *                     type="object",
     *                     @OA\Property(property="category", type="string", example="laptops"),
     *                     @OA\Property(
     *                         property="price_between",
     *                         type="array",
     *                         @OA\Items(type="number"),
     *                         example={500, 2000}
     *                     ),
     *                     @OA\Property(property="stock_below", type="integer", example=10)
     *                 ),
     *                 @OA\Property(
     *                     property="wishlisted",
     *                     type="object",
     *                     @OA\Property(property="category", type="string", example="smartphones"),
     *                     @OA\Property(
     *                         property="price_between",
     *                         type="array",
     *                         @OA\Items(type="number"),
     *                         example={100, 1000}
     *                     ),
     *                     @OA\Property(property="stock_below", type="integer", example=5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Seqment yaradıldı",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Example Segment"),
     *             @OA\Property(property="filter_json", type="object"),
     *             @OA\Property(property="member_count", type="integer", example=0),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validasiya xətası"),
     *     @OA\Response(response=401, description="Autentifikasiya tələb olunur")
     * )
     */
    public function createSegment() {}

    /**
     * @OA\Get(
     *     path="/segments/{id}",
     *     operationId="getSegment",
     *     tags={"Segments"},
     *     summary="Seqmentin detalları",
     *     description="ID-yə görə seqmentin tam məlumatını gətirir.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Seqment ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(response=200, description="Uğurlu cavab"),
     *     @OA\Response(response=404, description="Seqment tapılmadı"),
     *     @OA\Response(response=401, description="Autentifikasiya tələb olunur")
     * )
     */
    public function getSegment() {}

    /**
     * @OA\Get(
     *     path="/segments/{id}/preview",
     *     operationId="previewSegment",
     *     tags={"Segments"},
     *     summary="Seqment üzvlərinin önizləməsi",
     *     description="Seqmentə uyğun gələn istifadəçilərin siyahısını göstərir.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=8)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Seqment üzvləri",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_count", type="integer", example=150),
     *             @OA\Property(
     *                 property="preview",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="email", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Seqment tapılmadı")
     * )
     */
    public function previewSegment() {}

    /**
     * @OA\Get(
     *     path="/campaigns",
     *     operationId="getCampaigns",
     *     tags={"Campaigns"},
     *     summary="Kampaniyaların siyahısı",
     *     description="Filter və pagination ilə kampaniyaları gətirir.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Kampaniya statusuna görə filtr",
     *         required=false,
     *         @OA\Schema(type="string", enum={"draft", "scheduled", "sent", "failed"}, example="draft")
     *     ),
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", default=1, example=1)),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15, example=2)),
     *     @OA\Response(response=200, description="Uğurlu cavab"),
     *     @OA\Response(response=401, description="Autentifikasiya tələb olunur")
     * )
     */
    public function getCampaigns() {}

    /**
     * @OA\Post(
     *     path="/campaigns",
     *     operationId="createCampaign",
     *     tags={"Campaigns"},
     *     summary="Yeni kampaniya yarat",
     *     description="Idempotency-Key header ilə dublikat yaranmanı qarşısını alır.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="Idempotency-Key",
     *         in="header",
     *         description="Unikal key dublikat yaranmanın qarşısını alır",
     *         required=false,
     *         @OA\Schema(type="string", format="uuid", example="4dc3370a-9f50-4df5-8197-73a0489cf9e7")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"subject","template_key","segment_id","content"},
     *             @OA\Property(property="subject", type="string", example="Back to stock!"),
     *             @OA\Property(property="template_key", type="string", example="promo_a"),
     *             @OA\Property(property="segment_id", type="integer", example=7),
     *             @OA\Property(property="content", type="string", example="Email məzmunu...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Kampaniya yaradıldı",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="subject", type="string"),
     *             @OA\Property(property="template_key", type="string"),
     *             @OA\Property(property="segment_id", type="integer"),
     *             @OA\Property(property="status", type="string", example="draft"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validasiya xətası")
     * )
     */
    public function createCampaign() {}

    /**
     * @OA\Get(
     *     path="/campaigns/{id}",
     *     operationId="getCampaign",
     *     tags={"Campaigns"},
     *     summary="Kampaniyanın detalları",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Uğurlu cavab"),
     *     @OA\Response(response=404, description="Kampaniya tapılmadı")
     * )
     */
    public function getCampaign() {}

    /**
     * @OA\Get(
     *     path="/campaigns/{id}/stats",
     *     operationId="getCampaignStats",
     *     tags={"Campaigns"},
     *     summary="Kampaniya statistikası",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(
     *         response=200,
     *         description="Kampaniya statistikası",
     *         @OA\JsonContent(
     *             @OA\Property(property="campaign", type="object"),
     *             @OA\Property(
     *                 property="stats",
     *                 type="object",
     *                 @OA\Property(property="sent", type="integer", example=1000),
     *                 @OA\Property(property="delivered", type="integer", example=980),
     *                 @OA\Property(property="opened", type="integer", example=450),
     *                 @OA\Property(property="clicked", type="integer", example=120),
     *                 @OA\Property(property="bounced", type="integer", example=20),
     *                 @OA\Property(property="unsubscribed", type="integer", example=5)
     *             )
     *         )
     *     )
     * )
     */
    public function getCampaignStats() {}

    /**
     * @OA\Post(
     *     path="/campaigns/{id}/queue",
     *     operationId="queueCampaign",
     *     tags={"Campaigns"},
     *     summary="Kampaniyanı növbəyə əlavə et",
     *     description="Kampaniyanı göndərmə növbəsinə əlavə edir.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=8)),
     *     @OA\Response(
     *         response=200,
     *         description="Kampaniya növbəyə əlavə edildi",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Campaign queued successfully"))
     *     ),
     *     @OA\Response(response=400, description="Kampaniya artıq göndərilib")
     * )
     */
    public function queueCampaign() {}

    /**
     * @OA\Get(
     *     path="/unsubscribe/{userId}/{campaignId}",
     *     operationId="unsubscribe",
     *     tags={"Campaigns"},
     *     summary="Abunəlikdən çıxma",
     *     description="İmza ilə təsdiqlənmiş unsubscribe linki.",
     *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer", example=614)),
     *     @OA\Parameter(name="campaignId", in="path", required=true, @OA\Schema(type="integer", example=8)),
     *     @OA\Parameter(
     *         name="signature",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", example="442e4376ce7c22e33dd844d60d62beacda7dc7b985851e9062e4d8efc90af56d")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Abunəlikdən uğurla çıxarıldınız",
     *         @OA\JsonContent(@OA\Property(property="message", type="string"))
     *     ),
     *     @OA\Response(response=400, description="Yanlış imza")
     * )
     */
    public function unsubscribe() {}
}