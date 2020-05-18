<?php

namespace App;

use App\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Restql\Traits\RestqlAttributes;

class Author extends Model
{
    use RestqlAttributes;

    /**
     * Get the author articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * [getCreatingAttributes description]
     * @return [type] [description]
     */
    public function onCreateFillables(): array
    {
        return ['name', 'email', 'phone', 'address'];
    }
}
