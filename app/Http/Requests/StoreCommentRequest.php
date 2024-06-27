<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Comment;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'abbreviation' => 'required|regex:/^\S*$/|unique:comments,abbreviation',
            'content' => 'required|unique:comments,content',
            'post_id' => 'required|exists:posts,id'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->merge([
                'content' => strtolower($this->input('content')),
                'abbreviation' => strtolower($this->input('abbreviation')),
            ]);

            if ($this->hasDuplicateContent()) {
                $validator->errors()->add('content', 'The content is a duplicate considering word combinations.');
            }
        });
    }

    /**
     * Check for duplicate content considering word combinations.
     *
     * @return bool
     */
    protected function hasDuplicateContent()
    {
        $content = $this->input('content');
        $combinations = $this->generateCombinations($content);

        foreach ($combinations as $combination) {
            if (Comment::where('content', $combination)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate all possible word combinations from a given content string.
     *
     * @param string $content
     * @return array
     */
    protected function generateCombinations($content)
    {
        $words = explode(' ', $content);
        $combinations = [];

        $this->permutate($words, 0, count($words) - 1, $combinations);

        return $combinations;
    }

    /**
     * Recursively generate permutations of the words.
     *
     * @param array $items
     * @param int $left
     * @param int $right
     * @param array $combinations
     * @return void
     */
    protected function permutate($items, $left, $right, &$combinations)
    {
        if ($left == $right) {
            $combinations[] = implode(' ', $items);
        } else {
            for ($i = $left; $i <= $right; $i++) {
                $items = $this->swap($items, $left, $i);
                $this->permutate($items, $left + 1, $right, $combinations);
                $items = $this->swap($items, $left, $i);
            }
        }
    }

    /**
     * Swap the elements in the array.
     *
     * @param array $items
     * @param int $a
     * @param int $b
     * @return array
     */
    protected function swap($items, $a, $b)
    {
        $temp = $items[$a];
        $items[$a] = $items[$b];
        $items[$b] = $temp;

        return $items;
    }
}
