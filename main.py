import os
from telegram import Update
from telegram.ext import ApplicationBuilder, CommandHandler, MessageHandler, filters, ContextTypes

async def start(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    await update.message.reply_text('Привет! Я бот-калькулятор. Введите выражение для вычисления.')

async def calculate(update: Update, context: ContextTypes.DEFAULT_TYPE) -> None:
    expression = update.message.text
    try:
        result = eval(expression)
        await update.message.reply_text(f'Результат: {result}')
    except Exception as e:
        await update.message.reply_text('Ошибка в выражении.')

if __name__ == '__main__':
    application = ApplicationBuilder().token(os.getenv('BOT_TOKEN')).build()
    application.add_handler(CommandHandler('start', start))
    application.add_handler(MessageHandler(filters.TEXT & ~filters.COMMAND, calculate))
    application.run_polling()