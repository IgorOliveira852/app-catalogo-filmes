<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class MovieSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'query'              => $this->input('query')              ?: null,
            'type'               => $this->input('type')               ?: null,
            'page'               => $this->input('page')               ?: null,
            'genre'              => $this->input('genre')              ?: null,
            'sort_by'            => $this->input('sort_by')            ?: null,
            'release_date_gte'   => $this->input('release_date_gte')   ?: null,
            'release_date_lte'   => $this->input('release_date_lte')   ?: null,
            'vote_average_gte'   => $this->input('vote_average_gte')   ?: null,
            'vote_average_lte'   => $this->input('vote_average_lte')   ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'query'               => 'nullable|string',
            'type'                => 'nullable|in:movie,tv',
            'page'                => 'nullable|integer|min:1',
            'genre'               => 'nullable|integer',
            'sort_by'             => 'nullable|string',
            'release_date_gte'    => 'nullable|date',
            'release_date_lte'    => 'nullable|date|after_or_equal:release_date_gte',
            'vote_average_gte'    => 'nullable|numeric|min:0|max:10',
            'vote_average_lte'    => 'nullable|numeric|min:0|max:10|gte:vote_average_gte',
        ];
    }

    public function messages(): array
    {
        return [
            'release_date_lte.after_or_equal' => 'A data de lançamento final deve ser igual ou posterior à data de início.',
            'vote_average_lte.gte' => 'A média de votos final deve ser maior ou igual à média de votos inicial.',
            'vote_average_gte.min' => 'A média de votos inicial deve ser pelo menos 0.'
        ];
    }
}
