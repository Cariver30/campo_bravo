<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'background_image_cover',
        'background_image_menu',
        'background_image_cocktails',
        'background_image_wines',
        'logo',
        'text_color_cover',
        'text_color_menu',
        'text_color_cocktails',
        'text_color_wines',
        'card_opacity_cover',
        'card_opacity_menu',
        'card_opacity_cocktails',
        'card_opacity_wines',
        'font_family_cover',
        'font_family_menu',
        'font_family_cocktails',
        'font_family_wines',
        'button_color_cover',
        'button_color_menu',
        'button_color_cocktails',
        'button_color_wines',
        'category_name_bg_color_menu',
        'category_name_text_color_menu',
        'category_name_font_size_menu',
        'category_name_bg_color_cocktails',
        'category_name_text_color_cocktails',
        'category_name_font_size_cocktails',
        'category_name_bg_color_wines',
        'category_name_text_color_wines',
        'category_name_font_size_wines',
        'card_bg_color_menu',
        'card_bg_color_cocktails',
        'card_bg_color_wines',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'phone_number',
        'business_hours',
        'button_font_size_cover',
        'fixed_bottom_font_size',
        'fixed_bottom_font_color'
    ];
}
